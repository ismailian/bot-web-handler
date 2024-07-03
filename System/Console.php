<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System;

use GuzzleHttp\Client;
use TeleBot\System\Telegram\BotApi;
use TeleBot\System\Database\DbClient;
use TeleBot\System\Filesystem\Dotenv;
use GuzzleHttp\Exception\GuzzleException;

class Console
{

    protected static string $payload = "PD9waHAKCm5hbWVzcGFjZSBUZWxlQm90XEFwcFxIYW5kbGVyc3t7aGFuZGxlclBhdGh9fTsKCnVzZSBUZWxlQm90XFN5c3RlbVxJbmNvbWluZ0V2ZW50OwoKY2xhc3Mge3toYW5kbGVyTmFtZX19IGV4dGVuZHMgSW5jb21pbmdFdmVudCB7fQ==";
    protected static ?Client $client = null;
    protected static string $owner = 'ismailian';
    protected static string $repo = 'bot-web-handler';
    protected static string $history = 'history.json';

    /**
     * initialize update history file
     *
     * @return void
     */
    public static function init(): void
    {
        if (!file_exists(self::$history)) {
            file_put_contents(self::$history, json_encode([
                'date' => (new \DateTime())->format('Y-m-d\TH:i:s\Z'),
                'changes' => [],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $historyFileMessage = '[+] history file has been created!';
        } else {
            $historyFileMessage = '[!] history file already exists!';
        }

        echo $historyFileMessage . PHP_EOL;
    }

    /**
     * run migrations
     *
     * @param array $args
     * @return void
     */
    public static function migrate(array $args): void
    {
        $tables = ['users', 'events', 'sessions'];
        if (array_key_exists('tables', $args)) {
            $tables = array_intersect($tables, $args['tables']);
        }

        $migrations = [
            'users' => "CREATE TABLE `users` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `user_id` VARCHAR(64) NOT NULL,
                            `firstname` VARCHAR(45) NOT NULL,
                            `lastname` VARCHAR(45) NULL DEFAULT NULL,
                            `language_code` VARCHAR(2) NULL DEFAULT 'en',
                            `is_bot` TINYINT(1) NOT NULL DEFAULT '0',
                            `is_premium` TINYINT(1) NULL DEFAULT NULL,
                            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
                            PRIMARY KEY (`id`),
                            UNIQUE INDEX `user_id` (`user_id`)
                        ) COLLATE='utf8mb4_unicode_ci';",
            'events' => "CREATE TABLE `events` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `user_id` INT NOT NULL,
                            `update_id` INT NOT NULL,
                            `update` JSON NOT NULL,
                            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
                            PRIMARY KEY (`id`),
                            UNIQUE INDEX `update_id` (`update_id`),
                            CONSTRAINT `user_id_to_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                        ) COLLATE='utf8mb4_unicode_ci';",
            'sessions' => "CREATE TABLE `sessions` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `session_id` VARCHAR(64) NOT NULL,
                    `data` JSON NOT NULL,
                    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
                    PRIMARY KEY (`id`, `session_id`),
                    UNIQUE INDEX `session_id` (`session_id`)
                ) COLLATE='utf8mb4_unicode_ci';"
        ];

        Dotenv::load();
        $db = new DbClient();
        $dbName = getenv('DATABASE_NAME', true);

        $tableNames = join(',', array_map(fn($tableName) => "'$tableName'", $tables));
        $existingTables = $db->getClient()->query("show tables where Tables_in_{$dbName} in ($tableNames)")->fetchAll();
        $existingTables = array_column($existingTables, 'Tables_in_' . $dbName);

        foreach ($tables as $table) {
            if (in_array($table, $existingTables)) {
                echo '[!] table already exists: ' . $table . PHP_EOL;
                continue;
            }

            echo "[+] running migration for: $table" . PHP_EOL;
            $db->raw($migrations[$table]);
        }

        echo "\n[OK] migrations completed!\n";
    }

    /**
     * update system
     *
     * @return void
     */
    public static function update(): void
    {
        $lastUpdate = (new \DateTime())->format('Y-m-d\T00:00:00\Z');
        if (file_exists(self::$history)) {
            $history = json_decode(file_get_contents(self::$history), true);
            $lastUpdate = $history['date'];
        }

        $updates = self::getCommits($lastUpdate, null, true);
        if (empty($updates)) {
            die('[+] system is up-to-date!');
        }

        foreach ($updates as $update) {
            foreach ($update['files'] as $file) {
                [$action, $color] = match ($file['status']) {
                    'added' => ['creating', '1;32'],
                    'modified' => ['updating', '1;33'],
                    'removed' => ['deleting', '1;31'],
                    'renamed' => ['renaming', '1;34']
                };

                echo "[+] \033[{$color}m{$action}\033[0m: {$file['filename']}" . PHP_EOL;
                if ($action == 'deleting') {
                    @unlink($file['filename']);
                } else if ($action == 'renamed') {
                    if (array_key_exists('preview_filename', $file)) {
                        @rename($file['previous_filename'], $file['filename']);
                    }
                } else {
                    if (!file_exists(dirname($file['filename']))) {
                        @mkdir(dirname($file['filename']));
                    }

                    file_put_contents($file['filename'], file_get_contents($file['url']));
                }
            }
        }

        /** save last update details */
        file_put_contents(self::$history, json_encode([
            'date' => (new \DateTime())->format('Y-m-d\TH:i:s\Z'),
            'changes' => $updates,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo '[+] all changes have been applied!' . PHP_EOL . PHP_EOL;
    }

    /**
     * get list of commits
     *
     * @param string|null $startDate
     * @param string|null $endTime
     * @param bool $autoFetchFiles
     * @return array
     */
    protected static function getCommits(string $startDate = null, string $endTime = null, bool $autoFetchFiles = false): array
    {
        try {
            $query = [];
            $uri = '/repos/' . self::$owner . '/' . self::$repo . '/commits';
            if ($startDate) $query['since'] = $startDate;
            if ($endTime) $query['until'] = $startDate;

            $response = self::getClient()->get($uri, ['query' => $query])->getBody();
            return array_reverse(array_map(function ($commit) use ($autoFetchFiles) {
                return [
                    'id' => $commit['sha'],
                    'author' => $commit['author']['login'],
                    'message' => $commit['commit']['message'],
                    'files' => $autoFetchFiles ? self::getCommitFiles($commit['sha']) : [],
                ];
            }, json_decode($response, true)));
        } catch (GuzzleException $e) {
            if (preg_match('/(403 rate limit exceeded)/', $e->getMessage())) {
                die('[!] Rate limit exceeded, please wait before trying again!');
            }
        }

        return [];
    }

    /**
     * get http client
     *
     * @return Client
     */
    protected static function getClient(): Client
    {
        if (!self::$client) {
            self::$client = new Client([
                'verify' => false,
                'base_uri' => 'https://api.github.com/',
                'headers' => [
                    'X-GitHub-Api-Version' => '2022-11-28'
                ]
            ]);
        }

        return self::$client;
    }

    /**
     * get commit files
     *
     * @param string $commit
     * @return array
     */
    protected static function getCommitFiles(string $commit): array
    {
        try {
            $uri = '/repos/' . self::$owner . '/' . self::$repo . '/commits/' . $commit;
            $response = self::getClient()->get($uri)->getBody();
            $files = array_filter(
                json_decode($response, true)['files'],
                fn($f) => !str_starts_with($f['filename'], 'App')
            );

            return array_map(fn($file) => ([
                'status' => $file['status'],
                'filename' => $file['filename'],
                'url' => $file['raw_url'],
                ...($file['status'] == 'renamed' ? ['previous_filename' => $file['previous_filename']] : [])
            ]), $files);
        } catch (GuzzleException $e) {
            if (preg_match('/(403 rate limit exceeded)/', $e->getMessage())) {
                die('[!] Rate limit exceeded, please wait before trying again!');
            }
        }
        return [];
    }

    /**
     * check for new changes
     *
     * @return void
     */
    public static function check(): void
    {
        $lastUpdate = (new \DateTime())->format('Y-m-d\T00:00:00\Z');
        if (file_exists(self::$history)) {
            $history = json_decode(file_get_contents(self::$history), true);
            $lastUpdate = $history['date'];
        }

        $updates = self::getCommits($lastUpdate, null, true);
        if (empty($updates)) {
            die('[+] system is up-to-date!' . PHP_EOL);
        }

        echo PHP_EOL . '[+] available changes:' . PHP_EOL;
        foreach ($updates as $update) {
            foreach ($update['files'] as $file) {
                echo "\t* {$file['filename']} [{$file['status']}]" . PHP_EOL;
            }
        }

        echo PHP_EOL;
    }

    /**
     * create new handler
     *
     * @param array $args
     * @return void
     */
    public static function makeHandler(array $args): void
    {
        try {
            $segments = explode('/', $args['name']);
            $fullPath = join(DIRECTORY_SEPARATOR, ['App', 'Handlers', ...$segments]);
            if (count($segments) > 1) {
                @mkdir(dirname($fullPath), recursive: true);
            }

            $fileName = basename($fullPath);
            if (str_ends_with($fullPath, '.php')) {
                $fullPath = str_replace('.php', '', $fullPath);
                $fileName = basename($fullPath);
            }

            $payload = base64_decode(self::$payload);
            $handlerPath = str_replace('.', '', dirname(join('\\', $segments)));
            if (!empty($handlerPath)) {
                $handlerPath = '\\' . $handlerPath;
            }

            $payload = str_replace('{{handlerPath}}', $handlerPath, $payload);
            $payload = str_replace('{{handlerName}}', $fileName, $payload);

            $handler = fopen("$fullPath.php", 'w');
            fwrite($handler, $payload);
            fclose($handler);

            die("[+] handler created successfully!" . PHP_EOL);
        } catch (\Exception) {}
        die("[-] failed to create handler!" . PHP_EOL);
    }

    /**
     * delete existing handler
     *
     * @param array $args
     * @return void
     */
    public static function deleteHandler(array $args): void
    {
        $segments = explode('/', $args['name']);
        $fullPath = join(DIRECTORY_SEPARATOR, ['App', 'Handlers', ...$segments]);
        if (!str_ends_with($fullPath, '.php')) {
            $fullPath .= '.php';
        }

        if (@unlink($fullPath)) {
            die("[+] handler deleted successfully!" . PHP_EOL);
        }

        echo "[-] failed to delete handler!" . PHP_EOL;
    }

    /**
     * set bot webhook
     *
     * @param array $args
     * @return void
     * @throws GuzzleException
     */
    public static function setWebhook(array $args): void
    {
        $webhookUrl = getenv('APP_DOMAIN', true);
        if (empty($webhookUrl)) {
            die('[APP_DOMAIN] in .env file is required!');
        }

        if ($webhookUrl == 'http://localhost') {
            die('[APP_DOMAIN] seems to be set to localhost which is not a valid webhook url!');
        }

        $webhookUrl .= str_ends_with($webhookUrl, '/') ? '' : '/';
        if (array_key_exists('uri', $args)) {
            $webhookUrl = rtrim($webhookUrl, '/') . '/' . ltrim($args['uri'], '/');
        }

        $api = (new BotApi())->setToken(getenv('TG_BOT_TOKEN', true));
        if (!$api->setWebhook($webhookUrl, getenv('TG_BOT_SIGNATURE', true))) {
            die('[-] failed to set bot webhook!' . PHP_EOL);
        }

        echo '[+] webhook set successfully!' . PHP_EOL;
    }

    /**
     * unset bot webhook
     *
     * @return void
     * @throws GuzzleException
     */
    public static function unsetWebhook(): void
    {
        $api = (new BotApi())->setToken(getenv('TG_BOT_TOKEN', true));
        if (!$api->deleteWebhook()) {
            die('[-] failed to delete bot webhook!' . PHP_EOL);
        }

        echo '[+] webhook deleted successfully!' . PHP_EOL;
    }

}
<?php

namespace TeleBot\System;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Cli
{

    protected static string $payload = "PD9waHAKCm5hbWVzcGFjZSBUZWxlQm90XEFwcFxIYW5kbGVyc3t7aGFuZGxlclBhdGh9fTsKCnVzZSBUZWxlQm90XFN5c3RlbVxCYXNlRXZlbnQ7CgpjbGFzcyB7e2hhbmRsZXJOYW1lfX0gZXh0ZW5kcyBCYXNlRXZlbnQge30=";
    protected static ?Client $client = null;
    protected static string $owner = 'ismailian';
    protected static string $repo = 'bot-web-handler';
    protected static string $history = 'history.json';

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
            return array_map(fn($file) => ([
                'status' => $file['status'],
                'filename' => $file['filename'],
                'url' => $file['raw_url'],
            ]), json_decode($response, true)['files']);
        } catch (GuzzleException $e) {
            if (preg_match('/(403 rate limit exceeded)/', $e->getMessage())) {
                die('[!] Rate limit exceeded, please wait before trying again!');
            }
        }
        return [];
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
        ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

        die(PHP_EOL . '[+] all changes have been applied!' . PHP_EOL);
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

        die("[-] failed to delete handler!" . PHP_EOL);
    }

}
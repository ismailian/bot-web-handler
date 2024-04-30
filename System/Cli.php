<?php

namespace TeleBot\System;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Cli
{

    protected static string $payload = "PD9waHAKCm5hbWVzcGFjZSBUZWxlQm90XEFwcFxIYW5kbGVyc1x7e2hhbmRsZXJQYXRofX07Cgp1c2UgVGVsZUJvdFxTeXN0ZW1cQmFzZUV2ZW50OwoKY2xhc3Mge3toYW5kbGVyTmFtZX19IGV4dGVuZHMgQmFzZUV2ZW50IHt9";
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
        $date = new \DateTime();
        $lastDate = $date->sub(\DateInterval::createFromDateString('1 day'))->format('Y-m-d\T00:00:00\Z');
        if (file_exists(self::$history)) {
            $history = json_decode(file_get_contents(self::$history), true);
            $lastDate = $history['date'];
        }

        $updates = self::getCommits($lastDate, null, true);
        if (empty($updates)) {
            die('[+] system is up-to-date!');
        }

        foreach ($updates as $update) {
            foreach ($update['files'] as $file) {
                $action = match ($file['status']) {
                    'added' => 'creating',
                    'modified' => 'updating',
                    'deleted' => 'deleting'
                };
                echo "[+] {$action}: {$file['filename']}" . PHP_EOL;
                if ($action == 'deleting') {
                    @unlink($file['filename']);
                } else {
                    file_put_contents($file['filename'], file_get_contents($file['url']));
                }
            }
        }

        /** save last update details */
        file_put_contents(self::$history, json_encode([
            'date' => (new \DateTime())->format('Y-m-d\T00:00:00\Z'),
            'changes' => $updates,
        ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }

    /**
     * check for new changes
     *
     * @return void
     */
    public static function check(): void
    {
        $date = new \DateTime();
        $lastDate = $date->sub(\DateInterval::createFromDateString('1 day'))->format('Y-m-d\T00:00:00\Z');
        if (file_exists(self::$history)) {
            $history = json_decode(file_get_contents(self::$history), true);
            $lastDate = $history['date'];
        }

        $updates = self::getCommits($lastDate, null, true);
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
            $payload = str_replace('{{handlerPath}}', dirname(join('\\', $segments)), $payload);
            $payload = str_replace('{{handlerName}}', $fileName, $payload);

            $handler = fopen("$fullPath.php", 'w');
            fwrite($handler, $payload);
            fclose($handler);

            echo "[+] handler created successfully!" . PHP_EOL;
            return;

        } catch (\Exception) {
        }
        echo "[-] failed to create handler!" . PHP_EOL;
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
            echo "[+] handler deleted successfully!" . PHP_EOL;
        } else {
            echo "[-] failed to delete handler!" . PHP_EOL;
        }
    }

}
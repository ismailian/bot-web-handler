<?php

namespace TeleBot\System\Core\Console\Commands;

use DateTime;
use GuzzleHttp\Client;
use TeleBot\System\Core\Console\Command;
use GuzzleHttp\Exception\GuzzleException;

class Update extends Command
{

    const string OWNER = 'ismailian';
    const string REPO = 'bot-web-handler';
    const string HISTORY = 'history.json';

    public string $command = 'update';
    public string $description = 'Check for available updates!';
    public array $arguments = [
        'type' => [
            'required' => false,
            'validation' => [
                'type' => 'regex',
                'pattern' => '/^(init|check|apply)$/',
            ],
        ],
    ];

    /** @var Client|null $client http client */
    protected ?Client $client = null;

    /**
     * @inheritDoc
     */
    public function handle(...$args): void
    {
        $type = match ($args['type']) {
            'apply' => 'apply',
            'check' => 'check',
            default => 'init',
        };

        $this->{$type}();
    }

    /**
     * initialize update history file
     *
     * @return void
     */
    protected function init(): void
    {
        if (!file_exists('.env')) {
            @copy('.env.sample', '.env');
        }

        if (!file_exists(self::HISTORY)) {
            file_put_contents(self::HISTORY, json_encode([
                'date' => new DateTime()->format('Y-m-d\TH:i:s\Z'),
                'changes' => [],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $historyFileMessage = '[+] history file has been created!';
        } else {
            $historyFileMessage = '[!] history file already exists!';
        }

        echo $historyFileMessage . PHP_EOL;
    }

    /**
     * get http client
     *
     * @return Client
     */
    protected function getClient(): Client
    {
        if (is_null($this->client)) {
            $this->client = http([
                'verify' => false,
                'base_uri' => 'https://api.github.com/',
                'headers' => [
                    'X-GitHub-Api-Version' => '2022-11-28'
                ]
            ]);
        }

        return $this->client;
    }

    /**
     * check for new changes
     *
     * @return void
     */
    protected function check(): void
    {
        $lastUpdate = new DateTime()->format('Y-m-d\T00:00:00\Z');
        if (file_exists(self::HISTORY)) {
            $history = json_decode(file_get_contents(self::HISTORY), true);
            $lastUpdate = $history['date'];
        }

        $updates = $this->getCommits($lastUpdate, null, true);
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
     * update system
     *
     * @return void
     */
    protected function update(): void
    {
        $lastUpdate = new DateTime()->format('Y-m-d\T00:00:00\Z');
        if (file_exists(self::HISTORY)) {
            $history = json_decode(file_get_contents(self::HISTORY), true);
            $lastUpdate = $history['date'];
        }

        $updates = $this->getCommits($lastUpdate, null, true);
        if (empty($updates)) {
            die('[+] system is up-to-date!' . PHP_EOL);
        }

        foreach ($updates as $update) {
            foreach ($update['files'] as $file) {
                [$action, $color] = match ($file['status']) {
                    'added' => ['creating', '1;32'],
                    'modified' => ['updating', '1;33'],
                    'removed' => ['deleting', '1;31'],
                    'renamed' => ['renaming', '1;34']
                };

                echo "[+] \033[{$color}m$action\033[0m: {$file['filename']}" . PHP_EOL;
                if ($action == 'deleting') {
                    @unlink($file['filename']);
                } else {
                    if (!file_exists(dirname($file['filename']))) {
                        @mkdir(dirname($file['filename']));
                    }

                    file_put_contents($file['filename'], file_get_contents($file['url']));

                    // delete previous file (if renamed)
                    if (array_key_exists('previous_filename', $file)) {
                        @unlink($file['previous_filename']);
                        @rmdir(dirname($file['previous_filename']));
                    }
                }
            }
        }

        /** save last update details */
        file_put_contents(self::HISTORY, json_encode([
            'date' => new DateTime()->format('Y-m-d\TH:i:s\Z'),
            'changes' => $updates,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo '[+] all changes have been applied!' . PHP_EOL . PHP_EOL;
    }

    /**
     * get a list of commits
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param bool $autoFetchFiles
     * @return array
     */
    protected function getCommits(?string $startDate = null, ?string $endDate = null, bool $autoFetchFiles = false): array
    {
        try {
            $query = [];
            $uri = '/repos/' . self::OWNER . '/' . self::REPO . '/commits';
            if ($startDate) $query['since'] = $startDate;
            if ($endDate) $query['until'] = $startDate;

            $response = $this->getClient()->get($uri, ['query' => $query])->getBody();
            return array_reverse(array_map(function ($commit) use ($autoFetchFiles) {
                $author = $commit['author']['login'] ?? $commit['commit']['author']['name'];
                return [
                    'id' => $commit['sha'],
                    'author' => $author,
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
    protected function getCommitFiles(string $commit): array
    {
        try {
            $uri = '/repos/' . self::OWNER . '/' . self::REPO . '/commits/' . $commit;
            $response = $this->getClient()->get($uri)->getBody();
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

            return [];
        }
    }
}
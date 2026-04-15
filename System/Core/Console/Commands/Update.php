<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2025 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $action = $args['type'] ?? 'init';
        if (!in_array($action, ['init', 'check', 'apply'])) {
            $this->log('Unknown action: ' . $action, true);
        }

        $this->{$action}();
    }

    /**
     * initialize update history file
     *
     * @return void
     */
    protected function init(): void
    {
        if (!file_exists(self::HISTORY)) {
            file_put_contents(self::HISTORY, json_encode([
                'date' => new DateTime()->format('Y-m-d\TH:i:s\Z'),
                'changes' => [],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $historyFileMessage = 'history file has been created!';
        } else {
            $historyFileMessage = 'history file already exists!';
        }

        $this->log($historyFileMessage);
    }

    /**
     * get http client
     *
     * @return Client
     */
    protected function getClient(): Client
    {
        if (is_null($this->client)) {
            $headers = ['X-GitHub-Api-Version' => '2022-11-28'];
            if (env('GITHUB_API_TOKEN')) {
                $headers['Authorization'] = 'Bearer ' . env('GITHUB_API_TOKEN');
            }

            $this->client = http([
                'base_uri' => 'https://api.github.com/',
                'headers' => $headers,
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
            $this->log('system is up-to-date!', true);
        }

        $this->log('available changes:');

        $rows = [];
        $counter = 1;
        foreach ($updates as $update) {
            foreach ($update['files'] as $file) {
                $rows[] = [
                    'num' => $counter++,
                    'file' => $file['filename'],
                    'type' => ucfirst($file['status']),
                ];
            }
        }

        $this->table($rows);
    }

    /**
     * apply updates
     *
     * @return void
     */
    protected function apply(): void
    {
        $lastUpdate = new DateTime()->format('Y-m-d\T00:00:00\Z');
        if (file_exists(self::HISTORY)) {
            $history = json_decode(file_get_contents(self::HISTORY), true);
            $lastUpdate = $history['date'];
        }

        $updates = $this->getCommits($lastUpdate, null, true);
        if (empty($updates)) {
            $this->log('system is up-to-date!', true);
        }

        $projectRoot = realpath(__DIR__ . '/../../../../');
        foreach ($updates as $update) {
            foreach ($update['files'] as $file) {
                [$action, $color] = match ($file['status']) {
                    'added' => ['creating', '1;32'],
                    'modified' => ['updating', '1;33'],
                    'removed' => ['deleting', '1;31'],
                    'renamed' => ['renaming', '1;34']
                };

                // Validate that the target path stays within the project root
                $targetPath = realpath($projectRoot . '/' . $file['filename']) ?: ($projectRoot . '/' . $file['filename']);
                if (!str_starts_with($targetPath, $projectRoot . DIRECTORY_SEPARATOR)
                    && !str_starts_with($targetPath, $projectRoot . '/')) {
                    $this->log("Skipping unsafe path: {$file['filename']}", true);
                    continue;
                }

                echo "[+] \033[{$color}m$action\033[0m: {$file['filename']}" . PHP_EOL;
                if ($action == 'deleting') {
                    @unlink($targetPath);
                } else {
                    // Validate the download URL is from GitHub's raw content domain
                    $parsedUrl = parse_url($file['url']);
                    if (($parsedUrl['host'] ?? '') !== 'raw.githubusercontent.com') {
                        $this->log("Skipping file with unexpected URL host: {$file['url']}", true);
                        continue;
                    }

                    if (!file_exists(dirname($targetPath))) {
                        @mkdir(dirname($targetPath), 0755, true);
                    }

                    $content = (string)$this->getClient()->get($file['url'])->getBody();
                    file_put_contents($targetPath, $content);

                    // delete previous file (if renamed)
                    if (array_key_exists('previous_filename', $file)) {
                        $prevPath = realpath($projectRoot . '/' . $file['previous_filename']) ?: ($projectRoot . '/' . $file['previous_filename']);
                        if (str_starts_with($prevPath, $projectRoot . DIRECTORY_SEPARATOR)
                            || str_starts_with($prevPath, $projectRoot . '/')) {
                            @unlink($prevPath);
                            @rmdir(dirname($prevPath));
                        }
                    }
                }
            }
        }

        /** save last update details */
        file_put_contents(self::HISTORY, json_encode([
            'date' => new DateTime()->format('Y-m-d\TH:i:s\Z'),
            'changes' => $updates,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->log('all changes have been applied!');
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
            if ($endDate) $query['until'] = $endDate;

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
                $this->log('Rate limit exceeded, please wait before trying again!');
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
                $this->log('Rate limit exceeded, please wait before trying again!');
            }

            return [];
        }
    }

    /**
     * Print a table
     *
     * @param array $rows
     * @return void
     */
    protected function table(array $rows): void
    {
        $col1Width = max(array_map(fn($r) => strlen((string)$r['num']), $rows)) + 2;
        $col2Width = max(array_map(fn($r) => strlen($r['file']), $rows)) + 2;
        $col3Width = max(array_map(fn($r) => strlen($r['type']), $rows)) + 2;

        $times = $col1Width + $col2Width + $col3Width + 10;

        /** header */
        echo str_repeat('-', $times) . PHP_EOL;
        printf("| %-{$col1Width}s | %-{$col2Width}s | %-{$col3Width}s |\n", '*', 'File', 'Type');

        /** body */
        echo str_repeat('-', $times) . PHP_EOL;
        foreach ($rows as $r) {
            printf("| %-{$col1Width}s | %-{$col2Width}s | %-{$col3Width}s |\n", $r['num'], $r['file'], $r['type']);
        }

        echo str_repeat('-', $times) . PHP_EOL . PHP_EOL;
    }
}
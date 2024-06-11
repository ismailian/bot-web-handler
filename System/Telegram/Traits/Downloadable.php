<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait Downloadable
{

    /** @var Client|null $client http client */
    private static ?Client $client = null;

    /**
     * save file as the provided filename
     *
     * @param string|null $filename
     * @param string|null $directory
     * @return string|bool
     */
    public function save(string $filename = null, string $directory = null): string|bool
    {
        if (!$this->filePath) {
            if (!($this->filePath = $this->getFilePath())) {
                return false;
            }
        }

        /* write file to disk */
        $filename ??= basename($this->filePath);
        $buffer = $this->getFileContent();
        if ($directory) {
            $filename = $directory . (str_ends_with($directory, '/') ? '' : '/') . $filename;
        }

        return (file_put_contents($filename, $buffer) > 0) ? $filename : false;
    }

    /**
     * get file path
     *
     * @return string|null
     */
    private function getFilePath(): ?string
    {
        try {
            $uri = "/bot{token}/getFile";
            $token = getenv('TG_BOT_TOKEN', true);
            $response = $this->api()->get(
                str_replace('{token}', $token, $uri),
                ['query' => ['file_id' => $this->fileId]]
            );

            if ($response->getStatusCode() !== 200) return null;
            $body = json_decode($response->getBody(), true);

            return $body['ok'] ? $body['result']['file_path'] : null;
        } catch (GuzzleException $e) {}
        return null;
    }

    /**
     * get http client
     *
     * @return Client
     */
    private function api(): Client
    {
        if (empty(self::$client)) {
            self::$client = new Client([
                'base_uri' => 'https://api.telegram.org'
            ]);
        }

        return self::$client;
    }

    /**
     * download file buffer
     *
     * @return string|null
     */
    private function getFileContent(): ?string
    {
        try {
            $uri = "/file/bot{token}/" . $this->filePath;
            $token = getenv('TG_BOT_TOKEN', true);
            $response = $this->api()->get(str_replace('{token}', $token, $uri));

            if ($response->getStatusCode() !== 200) return null;
            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {}
        return null;
    }

}
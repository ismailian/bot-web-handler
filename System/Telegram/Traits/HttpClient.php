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
use TeleBot\System\Core\Logger;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

trait HttpClient
{

    /** @var ?Client $api */
    protected ?Client $api = null;

    /** @var string $baseUrl */
    protected string $baseUrl = 'https://api.telegram.org/bot{token}/';

    /** @var int $retryUpload number of attempts to retry failed upload */
    protected int $retryUpload = 3;

    /**
     * get data from Bot API
     *
     * @param string $method
     * @param array $query
     * @return array|null
     */
    protected function get(string $method, array $query = []): ?array
    {
        try {
            $endpoint = $this->baseUrl . $method;
            $endpoint = str_replace('{token}', $this->token, $endpoint);
            $response = $this->api->request('GET', $endpoint, [
                'query' => ['chat_id' => $this->chatId, ...$query]
            ]);

            if ($response->getStatusCode() !== 200) return null;
            $body = json_decode($response->getBody(), true);

            return $body['ok'] ? $body : null;
        } catch (GuzzleException|RequestException $e) {
            $this->log($e);
        }

        return null;
    }

    /**
     * determine whether to log the exception
     *
     * @param $exception
     * @return void
     */
    protected function log($exception): void
    {
        $resolved = false;
        $shouldLog = getenv('TG_LOG_EXCEPTIONS', true) === 'true';

        $thrown = $this->throw($exception);
        if (method_exists($exception, 'getResponse')) {
            $code = $exception->getResponse()->getStatusCode();
            $description = $exception->getResponse()->getBody()->getContents();
            if (($json = json_decode($description, true))) {
                $description = $json;
            }

            $resolved = $this->resolve($code, $description);
        }

        if ($shouldLog && !$resolved && !$thrown) {
            Logger::onException($exception);
        }
    }

    /**
     * send request to API
     *
     * @param string $method
     * @param array $data
     * @param int $attempts
     * @return array|null
     */
    protected function post(string $method, array $data, int $attempts = 1): ?array
    {
        try {
            $endpoint = $this->baseUrl . $method;
            $endpoint = str_replace('{token}', $this->token, $endpoint);

            /** use [multipart] when uploading */
            $withBuffer = ['photo', 'video', 'audio', 'document', 'voice', 'editMedia', 'sendMediaGroup'];
            if (in_array($method, $withBuffer)) {
                $ak = fn($arr) => array_keys($arr);
                $av = fn($arr) => array_values($arr);
                $jsonify = fn($input) => is_array($input) ? json_encode($input) : $input;
                $response = $this->api->request('POST', $endpoint, [
                    'multipart' => [
                        ['name' => 'chat_id', 'contents' => $this->chatId],
                        ...array_map(fn($k, $v) => ['name' => $k, 'contents' => $jsonify($v)], $ak($this->options), $av($this->options)),
                        ...array_map(fn($k, $v) => ['name' => $k, 'contents' => $v], $ak($data), $av($data)),
                    ],
                ]);
            } else {
                $response = $this->api->request('POST', $endpoint, [
                    'json' => ['chat_id' => $this->chatId, ...$data, ...$this->options]
                ]);
            }

            if ($response->getStatusCode() !== 200) return null;
            $body = json_decode($response->getBody(), true);

            /* store last message id */
            // todo: refactor this to reflect used method instead
            if (in_array($method, ['message', 'photo', 'video', 'audio', 'voice', 'document', 'contact'])) {
                $this->lastMessageId = $body['result']['message_id'];

                /** store last upload id */
                // todo: refactor this to reflect used method instead
                if (in_array($method, ['photo', 'video', 'audio', 'voice', 'document'])) {
                    $this->lastUploadId = $body['result'][$method]['file_id'] ?? null;
                }
            }

            $this->mode = null;
            $this->options = [];

            return $body['ok'] ? $body : null;
        } catch (GuzzleException|RequestException $e) {
            if ($withBuffer) {
                if ($this->retryUpload > 0 && $attempts < $this->retryUpload) {
                    return $this->post($method, $data, $attempts + 1);
                }
            }

            $this->log($e);
        }

        return null;
    }

}
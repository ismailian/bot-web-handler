<?php

namespace TeleBot\System\Telegram;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\GuzzleException;
use TeleBot\System\Telegram\Types\IncomingDice;
use TeleBot\System\Telegram\Types\IncomingPhoto;
use TeleBot\System\Telegram\Types\IncomingVideo;
use TeleBot\System\Telegram\Types\IncomingDocument;

class BotClient
{

    /** @var string $baseUrl */
    protected string $baseUrl = 'https://api.telegram.org/bot{token}/';

    /** @var string $token */
    protected string $token = '';

    /** @var string $chatId */
    protected string $chatId = '';

    /** @var string $mode */
    protected string $mode = 'html';

    /** @var ?Client $api */
    protected ?Client $api = null;

    /** @var array $options options to send with the message */
    protected array $options = [];

    /** @var string $lastMessageId */
    protected string $lastMessageId;

    /** @var ?string $lastUploadId */
    protected ?string $lastUploadId;

    /** @var array $endpoints */
    protected array $endpoints = [
        'updates' => 'getUpdates',
        'message' => 'sendMessage',
        'photo' => 'sendPhoto',
        'video' => 'sendVideo',
        'document' => 'sendDocument',
        'delete' => 'deleteMessage',
        'action' => 'sendChatAction',
        'user' => 'getChatMember',
        'dice' => 'sendDice',
        'edit' => 'editMessageText',
        'editMedia' => 'editMessageMedia',
        'sendMediaGroup' => 'sendMediaGroup'
    ];

    /**
     * default constructor
     */
    function __construct()
    {
        $this->api = new Client();
    }

    /**
     * set bot token
     *
     * @param string $token bot token to be used in requests
     * @return BotClient
     */
    public function setToken(string $token): BotClient
    {
        $this->token = $token;
        return $this;
    }

    /**
     * set message parse mode
     *
     * @param string $mode mode to use with message
     * @return BotClient
     */
    public function setParseMode(string $mode = 'text'): BotClient
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * set recipient chat id
     *
     * @param string $chatId recipient chat id
     * @return BotClient
     */
    public function setChatId(string $chatId): BotClient
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * extra options to send with the message
     *
     * @param array $options
     * @return BotClient
     */
    public function withOptions(array $options): BotClient
    {
        $this->options = [...$this->options, ...$options];
        return $this;
    }

    /**
     * send a text message
     *
     * @param string $text text message to send
     * @param bool $withAction send action
     * @return bool returns true on success, otherwise false
     * @throws Exception|GuzzleException
     */
    public function sendMessage(string $text, bool $withAction = false): bool
    {
        if ($withAction) $this->sendAction('typing');
        $data = $this->post('message', [
            'text' => $text,
            'parse_mode' => $this->mode,
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * send an action
     *
     * @param string $action action to send
     * @return BotClient
     * @throws Exception|GuzzleException
     */
    public function sendAction(string $action): BotClient
    {
        $this->post('action', [
            'chat_id' => $this->chatId,
            'action' => $action
        ]);

        return $this;
    }

    /**
     * send request to API
     *
     * @param string $action
     * @param array $data
     * @return array|null
     * @throws Exception|GuzzleException
     */
    protected function post(string $action, array $data): ?array
    {
        try {
            $endpoint = $this->baseUrl . $this->endpoints[$action];
            $endpoint = str_replace('{token}', $this->token, $endpoint);

            /** use [multipart] when uploading */
            $withBuffer = ['photo', 'video', 'audio', 'document', 'voice', 'editMedia', 'sendMediaGroup'];
            if (in_array($action, $withBuffer)) {
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
            if (in_array($action, ['message', 'photo', 'video', 'audio', 'voice', 'document', 'contact'])) {
                $this->lastMessageId = $body['result']['message_id'];

                /** store last upload id */
                if (in_array($action, ['photo', 'video', 'audio', 'voice', 'document'])) {
                    $this->lastUploadId = $body['result'][$action]['file_id'] ?? null;
                }
            }

            return $body['ok'] ? $body : null;
        } catch (Exception $e) {}
        return null;
    }

    /**
     * send a photo message with caption
     *
     * @param string $imagePath image path
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @return IncomingPhoto|bool returns IncomingPhoto on success, otherwise false
     * @throws Exception|GuzzleException
     */
    public function sendPhoto(string $imagePath, string $caption = null, bool $withAction = false, bool $asUrl = false): IncomingPhoto|bool
    {
        if ($withAction) $this->sendAction('upload_photo');
        $data = $this->post('photo', [
            'photo' => $asUrl ? $imagePath : Utils::tryFopen($imagePath, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        return ($data && $data['ok']) ? new IncomingPhoto($data['result']['photo']) : false;
    }

    /**
     * send a video message with caption
     *
     * @param string $videoPath the video url to send
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @param bool $asUrl weather the provider video is an ID or Url
     * @return IncomingVideo|bool returns IncomingVideo on success, otherwise false
     * @throws Exception|GuzzleException
     */
    public function sendVideo(string $videoPath, string $caption = null, bool $withAction = false, bool $asUrl = false): IncomingVideo|bool
    {
        if ($withAction) $this->sendAction('upload_video');
        $data = $this->post('video', [
            'video' => $asUrl ? $videoPath : Utils::tryFopen($videoPath, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        return ($data && $data['ok']) ? new IncomingVideo($data['result']['video']) : false;
    }

    /**
     * send a document message with caption
     *
     * @param string $fileUrl
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @param bool $asUrl
     * @return IncomingDocument|bool returns IncomingDocument on success, otherwise false
     * @throws Exception|GuzzleException
     */
    public function sendDocument(string $fileUrl, string $caption = null, bool $withAction = false, bool $asUrl = false): IncomingDocument|bool
    {
        if ($withAction) $this->sendAction('upload_document');
        $data = $this->post('document', [
            'document' => $asUrl ? $fileUrl : Utils::tryFopen($fileUrl, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        return ($data && $data['ok']) ? new IncomingDocument($data['result']['document']) : false;
    }

    /**
     * send a die message
     *
     * @param string $emoji
     * @return IncomingDice|bool
     * @throws Exception|GuzzleException
     */
    public function sendDice(string $emoji): IncomingDice|bool
    {
        $data = $this->post('dice', [
            'emoji' => $emoji,
        ]);

        return ($data && $data['ok']) ? new IncomingDice($data['result']['dice']) : false;
    }

    /**
     * set message to reply to
     *
     * @param int $messageId
     * @param string|null $chatId
     * @return $this
     */
    public function replyTo(int $messageId, string $chatId = null): self
    {
        $this->options['reply_parameters']['message_id'] = $messageId;
        if ($chatId) {
            $this->options['reply_parameters']['chat_id'] = $chatId;
        }

        return $this;
    }

    /**
     * delete last message sent by bot
     *
     * @return BotClient
     * @throws Exception|GuzzleException
     */
    public function deleteLastMessage(): BotClient
    {
        if (!empty($this->lastMessageId)) {
            $this->deleteMessage($this->lastMessageId);
            $this->lastMessageId = '';
        }

        return $this;
    }

    /**
     * delete a message
     *
     * @param string $messageId id of message to delete
     * @return bool
     * @throws Exception|GuzzleException
     */
    public function deleteMessage(string $messageId): bool
    {
        $data = $this->post('delete', [
            'message_id' => $messageId
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * get user info
     *
     * @param string $userId
     * @param bool $withPicture
     * @return array|null
     */
    public function getUser(string $userId, bool $withPicture = false): ?array
    {
        $data = $this->get('user', ['user_id' => $userId]);
        if (empty($data)) return null;

        return $data['result']['user'];
    }

    /**
     * send request to API
     *
     * @param string $action
     * @param array $query
     * @return array|null
     */
    protected function get(string $action, array $query): ?array
    {
        try {
            $endpoint = $this->baseUrl . $this->endpoints[$action];
            $endpoint = str_replace('{token}', $this->token, $endpoint);
            $response = $this->api->request('GET', $endpoint, [
                'query' => ['chat_id' => $this->chatId, ...$query]
            ]);

            if ($response->getStatusCode() !== 200) return null;
            $body = json_decode($response->getBody(), true);

            return $body['ok'] ? $body : null;
        } catch (GuzzleException $e) {}
        return null;
    }

    /**
     * get last sent message id
     *
     * @return int|null
     */
    public function getLastMessageId(): ?int
    {
        return $this->lastMessageId ?? null;
    }

    /**
     * get last sent video id
     *
     * @return string|null
     */
    public function getLastUploadId(): ?string
    {
        return $this->lastUploadId;
    }

    /**
     * edit message text
     *
     * @param string $messageId
     * @param string $text
     * @return bool
     * @throws Exception|GuzzleException
     */
    public function editMessage(string $messageId, string $text): bool
    {
        $data = $this->post('edit', [
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => $this->mode
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * edit media message
     *
     * @param string $messageId
     * @param string $type
     * @param string $mediaPath
     * @param string|null $caption
     * @param bool $asUrl
     * @return bool
     * @throws GuzzleException
     */
    public function editMedia(string $messageId, string $type, string $mediaPath, string $caption = null, bool $asUrl = false): bool
    {
        $data = $this->post('editMedia', [
            'message_id' => $messageId,
            'media' => json_encode([
                'type' => $type,
                'caption' => $caption ?? '',
                'parse_mode' => $this->mode,
                'media' => 'attach://media_file',
            ]),
            'media_file' => $asUrl ? $mediaPath : Utils::tryFopen($mediaPath, 'r'),
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * send photo album
     *
     * @param string $type
     * @param array $files
     * @param string|null $caption
     * @param bool $asUrl
     * @return bool
     * @throws GuzzleException
     */
    public function sendMediaGroup(string $type, array $files, string $caption = null, bool $asUrl = false): bool
    {
        $media = [];
        $attachments = [];
        foreach ($files as $index => $file) {
            $attachments["{$type}_$index"] = $asUrl ? $file : Utils::tryFopen($file, 'r');
            $media[] = [
                'type' => $type,
                'media' => "attach://{$type}_$index",
                'caption' => $caption ?? ''
            ];
        }

        $data = $this->post('sendMediaGroup', [
            'media' => json_encode($media),
            ...$attachments,
            'caption' => 'Monthly Overview For May 2024'
        ]);

        return $data && $data['ok'] == true;
    }
    
    /**
     * send invoice
     *
     * @param string $title
     * @param string $description
     * @param string $payload
     * @param array $prices
     * @param string $currency
     * @param string $startParameter
     * @param string|null $providerToken
     * @param string|null $photoUrl
     * @return bool
     * @throws GuzzleException
     */
    public function sendInvoice(string $title, string $description, string $payload, array $prices, string $currency = 'USD', string $startParameter = 'single-chat', string $providerToken = null, string $photoUrl = null): bool
    {
        $data = $this->post($this->endpoints['invoice'], [
            'title' => $title,
            'description' => $description,
            'payload' => $payload,
            'start_parameter' => $startParameter,
            'provider_token' => $providerToken,
            'currency' => $currency,
            'prices' => json_encode($prices),
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * answer a pre-checkout query
     *
     * @param string $queryId
     * @param bool $ok
     * @param string|null $errorMessage
     * @return bool
     * @throws GuzzleException
     */
    public function answerPreCheckoutQuery(string $queryId, bool $ok, string $errorMessage = null): bool
    {
        $data = $this->post($this->endpoints['checkout'], [
            'ok' => $ok,
            'pre_checkout_query_id' => $queryId,
            ...($errorMessage ? ['error_message' => $errorMessage] : [])
        ]);

        return $data && $data['ok'] == true;
    }
}
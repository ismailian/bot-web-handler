<?php

namespace TeleBot\System\Telegram;

use Exception;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\GuzzleException;
use TeleBot\System\Telegram\Types\IncomingAudio;
use TeleBot\System\Telegram\Types\Message;
use TeleBot\System\Telegram\Traits\Extensions;
use TeleBot\System\Telegram\Traits\HttpClient;
use TeleBot\System\Telegram\Types\IncomingDice;
use TeleBot\System\Telegram\Types\IncomingPhoto;
use TeleBot\System\Telegram\Types\IncomingVideo;
use TeleBot\System\Telegram\Types\IncomingDocument;

class BotClient
{

    use HttpClient, Extensions;

    /**
     * send a text message
     *
     * @param string $text text message to send
     * @param bool $withAction send action
     * @return Message|bool returns true on success, otherwise false
     * @throws Exception|GuzzleException
     */
    public function sendMessage(string $text, bool $withAction = false): Message|bool
    {
        if ($withAction) $this->withAction('typing');
        $data = $this->post('message', [
            'text' => $text,
            'parse_mode' => $this->mode,
        ]);

        if ($data && array_key_exists('result', $data)) {
            return new Message($data['result']);
        }

        return false;
    }

    /**
     * send a photo message with caption
     *
     * @param string $imagePath image path
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @return IncomingPhoto|Message|bool returns IncomingPhoto on success, otherwise false
     * @throws Exception|GuzzleException
     */
    public function sendPhoto(
        string $imagePath,
        string $caption = null,
        bool   $withAction = false,
        bool   $asUrl = false
    ): IncomingPhoto|Message|bool
    {
        if ($withAction) $this->withAction('upload_photo');
        $data = $this->post('photo', [
            'photo' => $asUrl ? $imagePath : Utils::tryFopen($imagePath, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('photo', $data['result'])) {
                return new IncomingPhoto($data['result']['photo']);
            }

            return new Message($data['result']);
        }

        return false;
    }

    /**
     * send a video message with caption
     *
     * @param string $videoPath the video url to send
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @param bool $asUrl weather the provider video is an ID or Url
     * @return IncomingVideo|Message|bool returns IncomingVideo on success, otherwise false
     * @throws Exception|GuzzleException
     */
    public function sendVideo(
        string $videoPath,
        string $caption = null,
        bool   $withAction = false,
        bool   $asUrl = false
    ): IncomingVideo|Message|bool
    {
        if ($withAction) $this->withAction('upload_video');
        $data = $this->post('video', [
            'video' => $asUrl ? $videoPath : Utils::tryFopen($videoPath, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('video', $data['result'])) {
                return new IncomingVideo($data['result']['video']);
            }

            return new Message($data['result']);
        }

        return false;
    }

    /**
     * send audio file
     *
     * @param string $audioFile
     * @param string|null $caption
     * @param bool $withAction
     * @param bool $asUrl
     * @return IncomingAudio|Message|bool
     * @throws GuzzleException
     */
    public function sendAudio(
        string $audioFile,
        string $caption = null,
        bool   $withAction = false,
        bool   $asUrl = false
    ): IncomingAudio|Message|bool
    {
        if ($withAction) $this->withAction('upload_audio');
        $data = $this->post('audio', [
            'audio' => $asUrl ? $audioFile : Utils::tryFopen($audioFile, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('audio', $data['result'])) {
                return new IncomingAudio($data['result']['audio']);
            }

            return new Message($data['result']);
        }

        return false;
    }

    /**
     * send a document message with caption
     *
     * @param string $fileUrl
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @param bool $asUrl
     * @return IncomingDocument|Message|bool returns IncomingDocument on success, otherwise false
     * @throws Exception|GuzzleException
     */
    public function sendDocument(
        string $fileUrl,
        string $caption = null,
        bool   $withAction = false,
        bool   $asUrl = false
    ): IncomingDocument|Message|bool
    {
        if ($withAction) $this->withAction('upload_document');
        $data = $this->post('document', [
            'document' => $asUrl ? $fileUrl : Utils::tryFopen($fileUrl, 'r'),
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('document', $data['result'])) {
                return new IncomingDocument($data['result']['document']);
            }

            return new Message($data['result']);
        }

        return false;
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

        return $data['result']['user'] ?? null;
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
    public function editMedia(
        string $messageId,
        string $type,
        string $mediaPath,
        string $caption = null,
        bool   $asUrl = false
    ): bool
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
            ...$attachments
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
    public function sendInvoice(
        string $title,
        string $description,
        string $payload,
        array  $prices,
        string $currency = 'USD',
        string $startParameter = 'single-chat',
        string $providerToken = null,
        string $photoUrl = null
    ): bool
    {
        $data = $this->post('invoice', [
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
        $data = $this->post('checkout', [
            'ok' => $ok,
            'pre_checkout_query_id' => $queryId,
            ...($errorMessage ? ['error_message' => $errorMessage] : [])
        ]);

        return $data && $data['ok'] == true;
    }
}
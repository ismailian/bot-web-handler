<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Traits\Methods;

use GuzzleHttp\Psr7\Utils;
use TeleBot\System\Telegram\Types\IncomingDice;
use TeleBot\System\Telegram\Types\IncomingPhoto;
use TeleBot\System\Telegram\Types\IncomingVideo;
use TeleBot\System\Telegram\Types\IncomingAudio;
use TeleBot\System\Telegram\Types\IncomingMessage;
use TeleBot\System\Telegram\Types\IncomingDocument;
use TeleBot\System\Telegram\Types\IncomingAnimation;

trait Send
{

    /**
     * send a text message
     *
     * @param string $text text message to send
     * @param bool $withAction send action
     * @return IncomingMessage|bool returns true on success, otherwise false
     */
    public function sendMessage(string $text, bool $withAction = false): IncomingMessage|bool
    {
        if ($withAction) $this->sendChatAction(self::TYPING);
        $data = $this->post(__FUNCTION__, [
            'text' => $text,
            ...($this->mode ? ['parse_mode' => $this->mode] : []),
        ]);

        if ($data && array_key_exists('result', $data)) {
            return new IncomingMessage($data['result']);
        }

        return false;
    }

    /**
     * send a photo message with caption
     *
     * @param string $imagePath image path
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @return IncomingPhoto|IncomingMessage|bool returns IncomingPhoto on success, otherwise false
     */
    public function sendPhoto(
        string $imagePath,
        string $caption = null,
        bool   $withAction = false,
        bool   $asUrl = false
    ): IncomingPhoto|IncomingMessage|bool
    {
        if ($withAction) $this->sendChatAction(self::UPLOAD_PHOTO);
        $data = $this->post(__FUNCTION__, [
            'photo' => $asUrl ? $imagePath : Utils::tryFopen($imagePath, 'r'),
            'caption' => $caption ?? '',
            ...($this->mode ? ['parse_mode' => $this->mode] : [])
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('photo', $data['result'])) {
                return new IncomingPhoto($data['result']['photo']);
            }

            return new IncomingMessage($data['result']);
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
     * @return IncomingVideo|IncomingMessage|bool returns IncomingVideo on success, otherwise false
     */
    public function sendVideo(
        string $videoPath,
        string $caption = null,
        bool   $withAction = false,
        bool   $asUrl = false
    ): IncomingVideo|IncomingMessage|bool
    {
        if ($withAction) $this->sendChatAction(self::UPLOAD_VIDEO);
        $data = $this->post(__FUNCTION__, [
            'video' => $asUrl ? $videoPath : Utils::tryFopen($videoPath, 'r'),
            'caption' => $caption ?? '',
            ...($this->mode ? ['parse_mode' => $this->mode] : [])
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('video', $data['result'])) {
                return new IncomingVideo($data['result']['video']);
            }

            return new IncomingMessage($data['result']);
        }

        return false;
    }

    /**
     * send audio file
     *
     * @param string $audioFile
     * @param string|null $caption
     * @param bool $asUrl
     * @return IncomingAudio|IncomingMessage|bool
     */
    public function sendAudio(
        string $audioFile,
        string $caption = null,
        bool   $asUrl = false
    ): IncomingAudio|IncomingMessage|bool
    {
        $data = $this->post(__FUNCTION__, [
            'audio' => $asUrl ? $audioFile : Utils::tryFopen($audioFile, 'r'),
            'caption' => $caption ?? '',
            ...($this->mode ? ['parse_mode' => $this->mode] : [])
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('audio', $data['result'])) {
                return new IncomingAudio($data['result']['audio']);
            }

            return new IncomingMessage($data['result']);
        }

        return false;
    }

    /**
     * send animation
     *
     * @param string $filePath
     * @param string|null $caption
     * @param bool $withAction
     * @param bool $asUrl
     * @return IncomingAnimation|IncomingMessage|bool
     */
    public function sendAnimation(
        string $filePath,
        string $caption = null,
        bool   $withAction = false,
        bool   $asUrl = false
    ): IncomingAnimation|IncomingMessage|bool
    {
        if ($withAction) $this->sendChatAction(self::CHOOSE_STICKER);
        $data = $this->post(__FUNCTION__, [
            'animation' => $asUrl ? $filePath : Utils::tryFopen($filePath, 'r'),
            'caption' => $caption ?? '',
            ...($this->mode ? ['parse_mode' => $this->mode] : [])
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('animation', $data['result'])) {
                return new IncomingAnimation($data['result']['animation']);
            }

            return new IncomingMessage($data['result']);
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
     * @return IncomingDocument|IncomingMessage|bool returns IncomingDocument on success, otherwise false
     */
    public function sendDocument(
        string $fileUrl,
        string $caption = null,
        bool   $withAction = false,
        bool   $asUrl = false
    ): IncomingDocument|IncomingMessage|bool
    {
        if ($withAction) $this->sendChatAction(self::UPLOAD_DOCUMENT);
        $data = $this->post(__FUNCTION__, [
            'document' => $asUrl ? $fileUrl : Utils::tryFopen($fileUrl, 'r'),
            'caption' => $caption ?? '',
            ...($this->mode ? ['parse_mode' => $this->mode] : [])
        ]);

        if ($data && array_key_exists('result', $data)) {
            if (array_key_exists('document', $data['result'])) {
                return new IncomingDocument($data['result']['document']);
            }

            return new IncomingMessage($data['result']);
        }

        return false;
    }

    /**
     * send a die message
     *
     * @param string $emoji
     * @return IncomingDice|bool
     */
    public function sendDice(string $emoji): IncomingDice|bool
    {
        $data = $this->post(__FUNCTION__, [
            'emoji' => $emoji,
        ]);

        return ($data && $data['ok']) ? new IncomingDice($data['result']['dice']) : false;
    }

    /**
     * send photo album
     *
     * @param string $type
     * @param array $files
     * @param string|null $caption
     * @param bool $asUrl
     * @return IncomingMessage[]|null
     */
    public function sendMediaGroup(
        string $type,
        array  $files,
        string $caption = null,
        bool   $asUrl = false
    ): ?array
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

        $data = $this->post(__FUNCTION__, [
            'media' => json_encode($media),
            ...$attachments
        ]);

        return $data && $data['ok']
            ? array_map(fn($m) => new IncomingMessage($m), $data['result'])
            : null;
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
        $data = $this->post(__FUNCTION__, [
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

}
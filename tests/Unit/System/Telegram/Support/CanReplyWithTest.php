<?php

declare(strict_types=1);

namespace TeleBot\System\Telegram\Support;

use TeleBot\System\Telegram\Enums\ParseMode;

class CanReplyWithBotStub
{
    public ?ParseMode $parseMode = null;
    public ?array $replyTo = null;
    public ?array $lastMessage = null;
    public ?array $lastPhoto = null;
    public ?array $lastVideo = null;
    public ?array $lastAudio = null;

    public function setParseMode(ParseMode $mode): void
    {
        $this->parseMode = $mode;
    }

    public function replyTo(int $id, ?int $chatId = null): self
    {
        $this->replyTo = [$id, $chatId];

        return $this;
    }

    public function sendMessage(string $message): bool
    {
        $this->lastMessage = [$message];

        return true;
    }

    public function sendPhoto(string $photo, ?string $caption = null): bool
    {
        $this->lastPhoto = [$photo, $caption];

        return true;
    }

    public function sendVideo(string $video, ?string $caption = null): bool
    {
        $this->lastVideo = [$video, $caption];

        return true;
    }

    public function sendAudio(string $audio, ?string $caption = null): bool
    {
        $this->lastAudio = [$audio, $caption];

        return true;
    }
}

function bot(): CanReplyWithBotStub
{
    static $instance = null;

    if ($instance === null) {
        $instance = new CanReplyWithBotStub();
    }

    return $instance;
}

namespace Tests\Unit\System\Telegram\Support;

use PHPUnit\Framework\TestCase;
use TeleBot\System\Telegram\Enums\ParseMode;
use TeleBot\System\Telegram\Support\CanReplyWith;

class CanReplyWithTest extends TestCase
{
    public function testWithModeDelegatesParseModeToBot(): void
    {
        $subject = new CanReplyWith(42, 101);

        $result = $subject->withMode(ParseMode::HTML);

        $this->assertSame($subject, $result);
        $this->assertSame(ParseMode::HTML, \TeleBot\System\Telegram\Support\bot()->parseMode);
    }

    public function testWithTextRepliesUsingMessageAndChatIds(): void
    {
        $subject = new CanReplyWith(15, 77);

        $this->assertTrue($subject->withText('hello world'));

        $this->assertSame([15, 77], \TeleBot\System\Telegram\Support\bot()->replyTo);
        $this->assertSame(['hello world'], \TeleBot\System\Telegram\Support\bot()->lastMessage);
    }

    public function testWithMediaMethodsForwardArguments(): void
    {
        $subject = new CanReplyWith(7, 14);

        $this->assertTrue($subject->withPhoto('photo.png', 'caption'));
        $this->assertSame([7, 14], \TeleBot\System\Telegram\Support\bot()->replyTo);
        $this->assertSame(['photo.png', 'caption'], \TeleBot\System\Telegram\Support\bot()->lastPhoto);

        $this->assertTrue($subject->withVideo('video.mp4'));
        $this->assertSame(['video.mp4', null], \TeleBot\System\Telegram\Support\bot()->lastVideo);

        $this->assertTrue($subject->withAudio('audio.mp3', 'track'));
        $this->assertSame(['audio.mp3', 'track'], \TeleBot\System\Telegram\Support\bot()->lastAudio);
    }
}

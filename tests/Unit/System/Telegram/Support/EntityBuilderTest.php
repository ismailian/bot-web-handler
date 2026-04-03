<?php

declare(strict_types=1);

namespace Tests\Unit\System\Telegram\Support;

use PHPUnit\Framework\TestCase;
use TeleBot\System\Telegram\Support\EntityBuilder;
use TeleBot\System\Telegram\Types\User;

class EntityBuilderTest extends TestCase
{
    public function testItBuildsEntitiesInInsertionOrder(): void
    {
        $builder = new EntityBuilder();

        $result = $builder
            ->mention(0, 4)
            ->pre(5, 8, 'php')
            ->command(0, 5)
            ->toArray();

        $this->assertSame([
            ['type' => 'mention', 'offset' => 0, 'length' => 4],
            ['type' => 'pre', 'offset' => 5, 'length' => 8, 'language' => 'php'],
            ['type' => 'bot_command', 'offset' => 0, 'length' => 5],
        ], $result);
    }

    public function testOptionalFieldsAreSkippedWhenNotProvided(): void
    {
        $builder = new EntityBuilder();

        $result = $builder
            ->textLink(0, 3)
            ->customEmoji(4, 2)
            ->toArray();

        $this->assertSame([
            ['type' => 'text_link', 'offset' => 0, 'length' => 3],
            ['type' => 'custom_emoji', 'offset' => 4, 'length' => 2],
        ], $result);
    }

    public function testTextMentionSerializesTheProvidedUser(): void
    {
        $userPayload = [
            'id' => '1001',
            'first_name' => 'Unit',
            'is_bot' => false,
        ];

        $result = (new EntityBuilder())
            ->textMention(1, 6, new User($userPayload))
            ->toArray();

        $this->assertSame([
            [
                'type' => 'text_mention',
                'offset' => 1,
                'length' => 6,
                'user' => $userPayload,
            ],
        ], $result);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\System\Telegram\Support;

use PHPUnit\Framework\TestCase;
use stdClass;
use TeleBot\System\Telegram\Enums\PassBy;
use TeleBot\System\Telegram\Support\MediaIterator;

class MediaIteratorFixture
{
    use MediaIterator;

    public function __construct(?string $variable = null, array $media = [])
    {
        $this->variable = $variable;
        if ($variable !== null) {
            $this->{$variable} = $media;
        }
    }
}

class MediaIteratorTest extends TestCase
{
    public function testEachReturnsWithoutVariableOrData(): void
    {
        $fixture = new MediaIteratorFixture();
        $called = false;

        $fixture->each(function () use (&$called): void {
            $called = true;
        });

        $this->assertFalse($called);
    }

    public function testEachPassesClonesByDefault(): void
    {
        $item = new stdClass();
        $item->name = 'origin';

        $fixture = new MediaIteratorFixture('items', [$item]);

        $fixture->each(function (stdClass $media): void {
            $media->name = 'changed';
        });

        $this->assertSame('origin', $item->name);
    }

    public function testEachCanPassByReference(): void
    {
        $item = new stdClass();
        $item->name = 'origin';

        $fixture = new MediaIteratorFixture('items', [$item]);

        $fixture->each(function (stdClass $media): void {
            $media->name = 'changed';
        }, PassBy::Reference);

        $this->assertSame('changed', $item->name);
    }
}

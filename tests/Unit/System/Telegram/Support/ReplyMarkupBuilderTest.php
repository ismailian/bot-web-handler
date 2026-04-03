<?php

declare(strict_types=1);

namespace Tests\Unit\System\Telegram\Support;

use PHPUnit\Framework\TestCase;
use TeleBot\System\Telegram\Support\ReplyMarkupBuilder;

class ReplyMarkupBuilderTest extends TestCase
{
    public function testToArrayReturnsEmptyArrayByDefault(): void
    {
        $this->assertSame([], (new ReplyMarkupBuilder())->toArray());
    }
}

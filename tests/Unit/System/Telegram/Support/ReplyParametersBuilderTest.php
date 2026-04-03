<?php

declare(strict_types=1);

namespace Tests\Unit\System\Telegram\Support;

use PHPUnit\Framework\TestCase;
use TeleBot\System\Telegram\Support\ReplyParametersBuilder;

class ReplyParametersBuilderTest extends TestCase
{
    public function testToArrayReturnsEmptyArrayByDefault(): void
    {
        $this->assertSame([], (new ReplyParametersBuilder())->toArray());
    }
}

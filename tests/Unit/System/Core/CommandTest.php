<?php

declare(strict_types=1);

namespace Tests\Unit\System\Core;

use PHPUnit\Framework\TestCase;
use TeleBot\System\Core\Console\Command;

class CommandTest extends TestCase
{
    public function testGetHelpTextIncludesArgumentValidationMetadata(): void
    {
        $command = new class extends Command {
            public string $command = 'demo';
            public string $description = 'Demo command';
            public array $arguments = [
                'user' => [
                    'required' => true,
                    'validation' => [
                        'type' => 'regex',
                        'pattern' => '/^\\d+$/',
                    ],
                ],
                'limit' => [
                    'required' => false,
                    'type' => 'number',
                ],
            ];

            public function handle(...$args): void
            {
            }
        };

        $help = $command->getHelpText();

        $this->assertStringContainsString('Command: demo', $help);
        $this->assertStringContainsString('Description: Demo command', $help);
        $this->assertStringContainsString('user', $help);
        $this->assertStringContainsString('required', $help);
        $this->assertStringContainsString('pattern: /^\\d+$/', $help);
        $this->assertStringContainsString('limit', $help);
        $this->assertStringContainsString('optional', $help);
    }
}

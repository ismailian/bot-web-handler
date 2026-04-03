<?php

declare(strict_types=1);

namespace Tests\Unit\System\Telegram\Coverage;

use PHPUnit\Framework\TestCase;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;

class TelegramSurfaceSmokeTest extends TestCase
{
    public function testEventAndFilterClassesImplementIEvent(): void
    {
        $eventFiles = array_merge(
            glob(__DIR__ . '/../../../../../System/Telegram/Events/*.php') ?: [],
            glob(__DIR__ . '/../../../../../System/Telegram/Events/Messages/*.php') ?: [],
            glob(__DIR__ . '/../../../../../System/Telegram/Filters/*.php') ?: [],
        );

        $this->assertNotEmpty($eventFiles);

        foreach ($eventFiles as $file) {
            $class = $this->symbolFromFile($file);

            $this->assertTrue(class_exists($class), sprintf('Class [%s] should be autoloadable.', $class));
            $this->assertTrue(
                is_subclass_of($class, IEvent::class),
                sprintf('Class [%s] should implement [%s].', $class, IEvent::class)
            );
        }
    }

    public function testValidatorClassesImplementIValidator(): void
    {
        $validatorFiles = glob(__DIR__ . '/../../../../../System/Telegram/Validators/*.php') ?: [];

        $this->assertNotEmpty($validatorFiles);

        foreach ($validatorFiles as $file) {
            $class = $this->symbolFromFile($file);

            $this->assertTrue(class_exists($class), sprintf('Class [%s] should be autoloadable.', $class));
            $this->assertTrue(
                is_subclass_of($class, IValidator::class),
                sprintf('Class [%s] should implement [%s].', $class, IValidator::class)
            );
        }
    }

    public function testTelegramTraitsAreLoadable(): void
    {
        $traitFiles = glob(__DIR__ . '/../../../../../System/Telegram/Traits/*.php') ?: [];
        $methodTraitFiles = glob(__DIR__ . '/../../../../../System/Telegram/Traits/Methods/*.php') ?: [];
        $allTraitFiles = array_merge($traitFiles, $methodTraitFiles);

        $this->assertNotEmpty($allTraitFiles);

        foreach ($allTraitFiles as $file) {
            $trait = $this->symbolFromFile($file);

            $this->assertTrue(trait_exists($trait), sprintf('Trait [%s] should be autoloadable.', $trait));
        }
    }

    public function testTelegramTypeClassesAreLoadable(): void
    {
        $typeFiles = glob(__DIR__ . '/../../../../../System/Telegram/Types/*.php') ?: [];

        $this->assertNotEmpty($typeFiles);

        foreach ($typeFiles as $file) {
            $class = $this->symbolFromFile($file);

            $this->assertTrue(
                class_exists($class) || enum_exists($class),
                sprintf('Class or enum [%s] should be autoloadable.', $class)
            );
        }
    }

    private function symbolFromFile(string $absolutePath): string
    {
        $content = file_get_contents($absolutePath);
        $this->assertNotFalse($content, sprintf('Failed to read [%s].', $absolutePath));

        preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatch);
        preg_match('/\b(class|trait|enum)\s+([A-Za-z_][A-Za-z0-9_]*)\b/', $content, $symbolMatch);

        $this->assertArrayHasKey(1, $namespaceMatch, sprintf('Namespace was not found in [%s].', $absolutePath));
        $this->assertArrayHasKey(2, $symbolMatch, sprintf('Class/trait/enum name was not found in [%s].', $absolutePath));

        return trim($namespaceMatch[1]) . '\\' . trim($symbolMatch[2]);
    }
}

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
            $class = $this->classFromSystemPath($file);

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
            $class = $this->classFromSystemPath($file);

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
            $trait = $this->classFromSystemPath($file);

            $this->assertTrue(trait_exists($trait), sprintf('Trait [%s] should be autoloadable.', $trait));
        }
    }

    public function testTelegramTypeClassesAreLoadable(): void
    {
        $typeFiles = glob(__DIR__ . '/../../../../../System/Telegram/Types/*.php') ?: [];

        $this->assertNotEmpty($typeFiles);

        foreach ($typeFiles as $file) {
            $class = $this->classFromSystemPath($file);

            $this->assertTrue(class_exists($class), sprintf('Class [%s] should be autoloadable.', $class));
        }
    }

    private function classFromSystemPath(string $absolutePath): string
    {
        $normalized = str_replace('\\', '/', $absolutePath);
        $parts = explode('/System/', $normalized, 2);

        $this->assertCount(2, $parts, sprintf('Failed to map [%s] to class name.', $absolutePath));

        $relative = $parts[1];
        $withoutExt = preg_replace('/\\.php$/', '', $relative) ?: $relative;

        return 'TeleBot\\System\\' . str_replace('/', '\\', $withoutExt);
    }
}

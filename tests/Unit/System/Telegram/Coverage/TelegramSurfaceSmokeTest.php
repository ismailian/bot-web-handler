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
            ['name' => $class] = $this->symbolFromFile($file);

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
            ['name' => $class] = $this->symbolFromFile($file);

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
            ['name' => $symbol, 'kind' => $kind] = $this->symbolFromFile($file);

            if ($kind === 'trait') {
                $this->assertTrue(trait_exists($symbol), sprintf('Trait [%s] should be autoloadable.', $symbol));
                continue;
            }

            $this->assertTrue(
                class_exists($symbol) || enum_exists($symbol),
                sprintf('Non-trait symbol [%s] should be autoloadable.', $symbol)
            );
        }
    }

    public function testTelegramTypeClassesAreLoadable(): void
    {
        $typeFiles = glob(__DIR__ . '/../../../../../System/Telegram/Types/*.php') ?: [];

        $this->assertNotEmpty($typeFiles);

        foreach ($typeFiles as $file) {
            ['name' => $class] = $this->symbolFromFile($file);

            $this->assertTrue(
                class_exists($class) || enum_exists($class),
                sprintf('Class or enum [%s] should be autoloadable.', $class)
            );
        }
    }

    private function symbolFromFile(string $absolutePath): array
    {
        $content = file_get_contents($absolutePath);
        $this->assertNotFalse($content, sprintf('Failed to read [%s].', $absolutePath));

        $tokens = token_get_all($content);
        $namespace = '';
        $symbol = null;
        $kind = null;

        for ($i = 0, $count = count($tokens); $i < $count; $i++) {
            $token = $tokens[$i];
            if (!is_array($token)) {
                continue;
            }

            if ($token[0] === T_NAMESPACE) {
                $namespace = '';
                for ($j = $i + 1; $j < $count; $j++) {
                    $next = $tokens[$j];
                    if ($next === ';') {
                        break;
                    }

                    if (is_array($next) && in_array($next[0], [T_STRING, T_NAME_QUALIFIED, T_NS_SEPARATOR], true)) {
                        $namespace .= $next[1];
                    }
                }
            }

            if (in_array($token[0], [T_CLASS, T_TRAIT, T_ENUM], true)) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $next = $tokens[$j];
                    if (is_array($next) && $next[0] === T_STRING) {
                        $symbol = $next[1];
                        $kind = token_name($token[0]);
                        break 2;
                    }
                }
            }
        }

        $this->assertNotSame('', $namespace, sprintf('Namespace was not found in [%s].', $absolutePath));
        $this->assertNotNull($symbol, sprintf('Class/trait/enum name was not found in [%s].', $absolutePath));

        return [
            'name' => $namespace . '\\' . $symbol,
            'kind' => strtolower(str_replace('T_', '', (string)$kind)),
        ];
    }
}

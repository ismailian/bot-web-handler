<?php

declare(strict_types=1);

namespace Tests\Unit\System\Core;

use PHPUnit\Framework\TestCase;

class CoreSurfaceSmokeTest extends TestCase
{
    public function testCoreSymbolsAreAutoloadable(): void
    {
        $files = $this->collectCoreFiles();
        $this->assertNotEmpty($files);

        foreach ($files as $file) {
            ['name' => $symbol, 'kind' => $kind] = $this->symbolFromFile($file);

            if ($kind === 'trait') {
                $this->assertTrue(trait_exists($symbol), sprintf('Trait [%s] should be autoloadable.', $symbol));
                continue;
            }

            if ($kind === 'enum') {
                $this->assertTrue(enum_exists($symbol), sprintf('Enum [%s] should be autoloadable.', $symbol));
                continue;
            }

            $this->assertTrue(class_exists($symbol), sprintf('Class [%s] should be autoloadable.', $symbol));
        }
    }

    public function testConsoleCommandsExtendBaseCommand(): void
    {
        $commandFiles = glob(__DIR__ . '/../../../../System/Core/Console/Commands/*.php') ?: [];
        $this->assertNotEmpty($commandFiles);

        foreach ($commandFiles as $file) {
            ['name' => $class] = $this->symbolFromFile($file);

            $this->assertTrue(
                is_subclass_of($class, \TeleBot\System\Core\Console\Command::class),
                sprintf('Console command [%s] should extend base Command.', $class)
            );
        }
    }

    private function collectCoreFiles(): array
    {
        return array_merge(
            glob(__DIR__ . '/../../../../System/Core/*.php') ?: [],
            glob(__DIR__ . '/../../../../System/Core/Attributes/*.php') ?: [],
            glob(__DIR__ . '/../../../../System/Core/Enums/*.php') ?: [],
            glob(__DIR__ . '/../../../../System/Core/Traits/*.php') ?: [],
            glob(__DIR__ . '/../../../../System/Core/Console/*.php') ?: [],
            glob(__DIR__ . '/../../../../System/Core/Console/Commands/*.php') ?: [],
        );
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
                        $kind = strtolower(str_replace('T_', '', token_name($token[0])));
                        break 2;
                    }
                }
            }
        }

        $this->assertNotSame('', $namespace, sprintf('Namespace was not found in [%s].', $absolutePath));
        $this->assertNotNull($symbol, sprintf('Class/trait/enum name was not found in [%s].', $absolutePath));

        return [
            'name' => $namespace . '\\' . $symbol,
            'kind' => $kind,
        ];
    }
}

<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2025 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core\Console;

use Throwable;
use ReflectionMethod;
use TeleBot\System\Core\Filesystem;

class Console
{

    /** @var string $commandsPath path to load commands from */
    protected string $commandsPath;

    /** @var array $commands available commands */
    protected array $commands = [];

    /**
     * Default constructor
     *
     * @param string $commandsPath
     */
    public function __construct(string $commandsPath = 'App/Commands')
    {
        $commandsDirectories = [
            'system' => 'System/Core/Console/Commands',
            'app' => $commandsPath,
        ];

        foreach ($commandsDirectories as $commandsDirectory) {
            $this->commandsPath = rtrim($commandsDirectory, '/');
            $this->loadCommands();
        }
    }

    /**
     * Autoload command classes
     */
    protected function loadCommands(): void
    {
        if (!is_dir($this->commandsPath)) {
            return;
        }

        $pattern = $this->commandsPath . '/*.php';
        foreach (glob($pattern) as $file) {
            require_once $file;
            $className = pathinfo($file, PATHINFO_FILENAME);
            $commandClass = Filesystem::getNamespacedFile($className, $this->commandsPath);

            if (!class_exists($commandClass)) continue;

            $instance = new $commandClass();
            if (is_subclass_of($instance, Command::class)) {
                $this->commands[$instance->command] = $instance;
            }
        }
    }

    /**
     * Validate argument based on validation schema
     *
     * @param string $value
     * @param array|string|null $validation
     * @return bool
     */
    protected function validateArgument(string $value, array|string|null $validation): bool
    {
        if (is_string($validation)) {
            $validation = ['type' => $validation];
        }

        $type = $validation['type'] ?? 'string';

        switch ($type) {
            case 'regex':
                if (empty($validation['pattern'])) {
                    fwrite(STDERR, "Missing 'pattern' for regex validation.\n");
                    return false;
                }
                return preg_match($validation['pattern'], $value) === 1;

            case 'string':
                $length = strlen($value);
                if (isset($validation['min']) && $length < $validation['min']) return false;
                if (isset($validation['max']) && $length > $validation['max']) return false;
                return true;

            case 'int':
            case 'integer':
            case 'numeric':
                if (filter_var($value, FILTER_VALIDATE_INT) === false) return false;
                $intVal = (int)$value;
                if (isset($validation['min']) && $intVal < $validation['min']) return false;
                if (isset($validation['max']) && $intVal > $validation['max']) return false;
                return true;

            case 'bool':
            case 'boolean':
                return in_array(strtolower($value), ['true', 'false', '1', '0'], true);

            default:
                return true;
        }
    }

    /**
     * Cast validated argument value to correct PHP type
     *
     * @param string $value
     * @param string $type
     * @return bool|int|string
     */
    protected function castValue(string $value, string $type): bool|int|string
    {
        return match ($type) {
            'int', 'integer', 'numeric' => (int)$value,
            'bool', 'boolean' => in_array(strtolower($value), ['true', '1'], true),
            default => $value,
        };
    }

    /**
     * Display global help
     *
     * @param string $script
     */
    protected function displayGlobalHelp(string $script): void
    {
        echo "Usage:\n";
        echo "  php $script <command> [arguments...]\n\n";
        echo "Available Commands:\n";

        if (empty($this->commands)) {
            echo "  (No commands found in {$this->commandsPath})\n";
            return;
        }

        foreach ($this->commands as $cmd) {
            printf("  %-20s %s\n", $cmd->command, $cmd->description);
        }

        echo "\nUse '<command> --help' for more details.\n";
    }

    /**
     * Run CLI
     */
    public function run(): void
    {
        global $argv;
        $script = array_shift($argv);
        $cmd = $argv[0] ?? null;

        if (!$cmd || in_array($cmd, ['-h', '--help'])) {
            $this->displayGlobalHelp($script);
            exit(0);
        }

        if (!isset($this->commands[$cmd])) {
            fwrite(STDERR, "Unknown command: $cmd\n\n");
            $this->displayGlobalHelp($script);
            exit(1);
        }

        $command = $this->commands[$cmd];
        array_shift($argv);

        if (isset($argv[0]) && in_array($argv[0], ['-h', '--help'])) {
            echo $command->getHelpText();
            exit(0);
        }

        $argumentsMeta = $command->arguments;
        $params = [];
        $missing = [];

        foreach ($argumentsMeta as $argName => $meta) {
            $value = array_shift($argv);
            if ($value === null) {
                if (!empty($meta['required'])) {
                    $missing[] = $argName;
                }
                $params[$argName] = null;
                continue;
            }

            $validation = $meta['validation'] ?? ($meta['type'] ?? 'string');
            $isValid = $this->validateArgument($value, $validation);

            if (!$isValid) {
                fwrite(STDERR, "Invalid value for argument '$argName'.\n");
                exit(1);
            }

            $type = is_array($validation) ? ($validation['type'] ?? 'string') : $validation;
            $params[$argName] = $this->castValue($value, $type);
        }

        if (!empty($missing)) {
            fwrite(STDERR, "Missing required argument(s): " . implode(', ', $missing) . "\n");
            echo "\n" . $command->getHelpText();
            exit(1);
        }

        try {
            $ref = new ReflectionMethod($command, 'handle');
            $ref->invokeArgs($command, $params);
        } catch (Throwable $e) {
            fwrite(STDERR, "Error: {$e->getMessage()}\n");
            exit(1);
        }
    }

}
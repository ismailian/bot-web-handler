<?php

namespace TeleBot\System\Core\Console;

abstract class Command
{

    /** @var string $command command */
    public string $command = 'command';

    /** @var string $description command description */
    public string $description = 'Command description';

    /** @var array $arguments command arguments */
    public array $arguments = [];

    /**
     * Generate help text for this command
     *
     * @return string return command help text
     */
    public function getHelpText(): string
    {
        $help = "Command: {$this->command}\n";
        $help .= "Description: {$this->description}\n";

        if (!empty($this->arguments)) {
            $help .= "\nArguments:\n";
            foreach ($this->arguments as $name => $meta) {
                $req = $meta['required'] ? 'required' : 'optional';

                // Backward compatible
                $validation = $meta['validation'] ?? ($meta['type'] ?? ['type' => 'string']);
                if (is_string($validation)) {
                    $validation = ['type' => $validation];
                }

                $type = $validation['type'] ?? 'string';
                $extra = [];

                foreach (['min', 'max', 'pattern'] as $key) {
                    if (isset($validation[$key])) {
                        $extra[] = "$key: {$validation[$key]}";
                    }
                }

                $extraStr = empty($extra) ? '' : ' (' . implode(', ', $extra) . ')';
                $help .= sprintf("  %-15s [%s, type: %s%s]\n", $name, $req, $type, $extraStr);
            }
        } else {
            $help .= "\n(No arguments)\n";
        }

        return $help . PHP_EOL;
    }

    /**
     * Command handler
     *
     * @param mixed ...$args
     */
    abstract public function handle(...$args): void;
}
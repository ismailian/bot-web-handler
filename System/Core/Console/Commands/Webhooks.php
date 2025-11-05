<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2025 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core\Console\Commands;

use TeleBot\System\Core\Console\Command;

class Webhooks extends Command
{

    public string $command = 'webhook';
    public string $description = 'Set or delete telegram webhook';
    public array $arguments = [
        'action' => [
            'required' => true,
            'validation' => [
                'type' => 'string',
                'pattern' => '/^(set|delete)$/',
            ],
        ],
        'uri' => [
            'required' => false,
            'validation' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * Set webhook
     *
     * @param string|null $uri
     * @return void
     */
    protected function set(?string $uri = null): void
    {
        $webhookUrl = env('APP_DOMAIN');
        if (empty($webhookUrl)) {
            die('[APP_DOMAIN] in .env file is required!');
        }

        if ($webhookUrl == 'http://localhost') {
            die('[APP_DOMAIN] seems to be set to localhost which is not a valid webhook url!');
        }

        $webhookUrl .= str_ends_with($webhookUrl, '/') ? '' : '/';
        if ($uri) {
            $webhookUrl = rtrim($webhookUrl, '/') . '/' . ltrim($uri, '/');
        }

        if (!bot()->setWebhook($webhookUrl, env('TG_WEBHOOK_SIGNATURE'))) {
            die('[-] failed to set bot webhook!' . PHP_EOL);
        }

        echo '[+] webhook set successfully!' . PHP_EOL;
    }

    /**
     * Delete webhook
     *
     * @return void
     */
    protected function delete(): void
    {
        if (!bot()->deleteWebhook()) {
            die('[-] failed to delete bot webhook!' . PHP_EOL);
        }

        echo '[+] webhook deleted successfully!' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function handle(...$args): void
    {
        $action = $args['action'];
        $this->$action($args['uri'] ?? null);
    }
}
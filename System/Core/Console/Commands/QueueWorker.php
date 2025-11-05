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

class QueueWorker extends Command
{

    public string $command = 'queue';
    public string $description = 'Initialize or run the queue worker';
    public array $arguments = [
        'action' => [
            'required' => true,
            'validation' => [
                'type' => 'string',
                'pattern' => '/^(init|work)$/',
            ],
        ],
    ];

    /**
     * @inheritDoc
     */
    public function handle(...$args): void
    {
        switch ($args['action']) {
            case 'init': queue()->init(); break;
            case 'work': queue()->listen(); break;
            default: break;
        }
    }
}
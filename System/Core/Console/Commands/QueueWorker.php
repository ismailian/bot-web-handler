<?php

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
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

class Migration extends Command
{

    public string $command = 'migrate';
    public string $description = 'Run app migrations for (users, events, sessions)';
    public array $arguments = [
        'table' => [
            'required' => true,
            'validation' => [
                'type' => 'regex',
                'pattern' => '/^(users|events|sessions)$/',
            ],
        ],
    ];

    /** @var array|string[] $migrations */
    protected array $migrations = [
        'users' => "CREATE TABLE `users` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `user_id` VARCHAR(64) NOT NULL,
                            `firstname` VARCHAR(45) NOT NULL,
                            `lastname` VARCHAR(45) NULL DEFAULT NULL,
                            `language_code` VARCHAR(2) NULL DEFAULT 'en',
                            `is_bot` TINYINT(1) NOT NULL DEFAULT '0',
                            `is_premium` TINYINT(1) NULL DEFAULT NULL,
                            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
                            PRIMARY KEY (`id`),
                            UNIQUE INDEX `user_id` (`user_id`)
                        ) COLLATE='utf8mb4_unicode_ci';",
        'events' => "CREATE TABLE `events` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `user_id` INT NOT NULL,
                            `update_id` INT NOT NULL,
                            `update` JSON NOT NULL,
                            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
                            PRIMARY KEY (`id`),
                            UNIQUE INDEX `update_id` (`update_id`),
                            CONSTRAINT `user_id_to_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                        ) COLLATE='utf8mb4_unicode_ci';",
        'sessions' => "CREATE TABLE `sessions` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `session_id` VARCHAR(64) NOT NULL,
                    `data` JSON NOT NULL,
                    `ttl` VARCHAR(45) NULL DEFAULT NULL,
                    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
                    PRIMARY KEY (`id`, `session_id`),
                    UNIQUE INDEX `session_id` (`session_id`)
                ) COLLATE='utf8mb4_unicode_ci';"
    ];

    /**
     * @inheritDoc
     */
    public function handle(...$args): void
    {
        $dbName = env('DATABASE_NAME');
        $query = "SHOW TABLES WHERE Tables_in_$dbName IN ('{$args['table']}')";

        $existingTables = database()->getClient()->query($query)->fetchAll();
        $existingTables = array_column($existingTables, 'Tables_in_' . $dbName);

        if (in_array($args['table'], $existingTables)) {
            echo '[!] table already exists: ' . $args['table'] . PHP_EOL;
            return;
        }

        database()->raw($this->migrations[$args['table']]);
    }
}
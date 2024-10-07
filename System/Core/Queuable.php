<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core;

use TeleBot\System\Database\DbClient;

trait Queuable
{

    /** @var DbClient|null $db database client */
    protected static ?DbClient $db = null;

    /**
     * add a job to the queue
     *
     * @param array $data data to pass to the job class
     * @return void
     */
    public static function dispatch(array $data): void
    {
        self::$db ??= new DbClient();
        self::$db->insert('queue_jobs', [
            'job' => self::class,
            'data' => json_encode($data),
        ]);
    }

}
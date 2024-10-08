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

trait Queuable
{

    /** @var Database|null $db database client */
    protected static ?Database $db = null;

    /**
     * add a job to the queue
     *
     * @param array $data data to pass to the job class
     * @return void
     */
    public static function dispatch(array $data): void
    {
        self::$db ??= new Database();
        self::$db->insert('queue_jobs', [
            'job' => self::class,
            'data' => json_encode($data),
        ]);
    }

}
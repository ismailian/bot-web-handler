<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System;

use TeleBot\System\Database\DbClient;

class Queue
{

    /** @var int $SLEEP_TIME */
    protected static int $SLEEP_TIME = 300_000;

    /** @var int $RETRIES number of retries */
    protected static int $RETRIES = 3;

    /** @var DbClient|null $db database client */
    protected static ?DbClient $db = null;

    /**
     * run queue migrations
     *
     * @return void
     */
    public static function init(): void
    {
        self::$db ??= new DbClient();

        $tableExists = (bool)self::$db->getClient()->query("SHOW TABLES LIKE 'queue_jobs'")->rowCount();
        if (!$tableExists) {
            try {
                echo '[+] running migration for queue table.. ';
                self::$db->raw("CREATE TABLE `queue_jobs` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `status` TINYINT(1) NOT NULL DEFAULT 0,
                        `job` VARCHAR(255) NOT NULL,
                        `data` JSON NOT NULL,
                        `reserved_at` BIGINT(20) UNSIGNED NULL,
                        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
                        PRIMARY KEY (`id`)
                    ) COLLATE='latin1_swedish_ci';");
                echo '[OK]' . PHP_EOL;

                echo '[+] creating jobs directory.. ';
                @mkdir('App/Jobs');
                die('[OK]');
            } catch (\Exception $e) {
                die("failed!" . PHP_EOL . $e->getMessage() . PHP_EOL);
            }
        }

        echo '[+] queue table already exists.' . PHP_EOL;
    }

    /**
     * listen for queue jobs
     *
     * @return void
     */
    public static function listen(): void
    {
        self::$db ??= new DbClient();
        while (true) {
            $reservedAt = (int)str_replace('.', '', microtime(true));
            $rows = self::$db->run('UPDATE `queue_jobs` SET `reserved_at` = ? WHERE `status` = ? ORDER BY `id` LIMIT 1', [$reservedAt, 0])->rowCount();
            if ($rows > 0) {
                $job = self::$db->row('select id,job,data from `queue_jobs` where `reserved_at` = ?', [$reservedAt]);
                if ($job) {
                    self::$db->update('queue_jobs', ['status' => 1], ['id' => $job->id]);
                    self::runJob($job);
                    if (self::$SLEEP_TIME > 300_000) {
                        self::$SLEEP_TIME = 300_000;
                    }
                }
            } else {
                if (self::$SLEEP_TIME < 1_000_000) {
                    self::$SLEEP_TIME = 1_000_000;
                }
            }

            usleep(self::$SLEEP_TIME);
        }
    }

    /**
     * execute job
     *
     * @param object $job
     * @param int $attempts
     * @return void
     */
    private static function runJob(object $job, int $attempts = 1): void
    {
        $payload = json_decode($job->data, true);
        $status = 2;

        try {
            (new $job->job($job->id, $payload))->process();
        } catch (\Exception $e) {
            if ($attempts < self::$RETRIES) {
                self::runJob($job, $attempts + 1);
                return;
            }

            $status = -1;
        }

        self::$db->update('queue_jobs', ['status' => $status], ['id' => $job->id]);
    }

    /**
     * add job to the queue
     *
     * @param string $job job class
     * @param array $data data to pass to the job class
     * @return void
     */
    public static function dispatch(string $job, array $data): void
    {
        self::$db ??= new DbClient();
        self::$db->insert('queue_jobs', [
            'job' => $job,
            'data' => json_encode($data),
        ]);
    }

}
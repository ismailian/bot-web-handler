<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Database;

trait Mapper
{

    /**
     * map records to models.
     * 
     * @param string $className the model name.
     * @param array $results the database records.
     * @return array returns array of objects.
     */
    public static function map(string $className, array $results): array
    {
        $obj = new $className();
        $listOfObjects = [];

        foreach ($results as $record) {
            $newObj = new $className();
            foreach ($record as $attr => $value) {
                if (in_array($attr, ['id', 'created_at', 'updated_at'])) {
                    $newObj->$attr = $value;
                    continue;
                }

                if (in_array($attr, $obj::$attributes)) {
                    $newObj->$attr = $value;
                }
            }

            $listOfObjects[] = $newObj;
        }

        return $listOfObjects;
    }
}

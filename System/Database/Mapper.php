<?php

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

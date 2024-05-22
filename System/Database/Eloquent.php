<?php

namespace TeleBot\System\Database;

use Exception;

trait Eloquent
{

    /** @var ?DbClient $content database context */
    public static ?DbClient $context = null;

    /** @var string $table the table name. */
    public static string $table = '';

    /** @var string $type the model class name */
    public static string $type = '';

    /** @var array $attributes attributes */
    public static array $attributes = [];

    /**
     * get a single record
     *
     * @throws Exception
     */
    public static function fetch($fields = ['*']): array|object
    {
        $records = static::init()->limit(1)->select(static::$table, $fields);
        $objects = static::map(static::$type, (array)$records);

        return count($objects) > 0 ? $objects[0] : $objects;
    }

    /**
     * initialize db client
     *
     * @throws Exception
     */
    public static function init(): DbClient
    {
        if (is_null(static::$context) || !static::$context) {
            static::$context = DbClient::instance();
        }

        return static::$context;
    }

    /**
     * get all records
     *
     * @throws Exception
     */
    public static function fetchAll($fields = ['*']): array|object
    {
        $records = static::init()->select(static::$table, $fields);
        return static::map(static::$type, (array)$records);
    }

    /**
     * find records
     *
     * @param array $conditions find records by given conditions.
     * @throws Exception
     */
    public static function find(array $conditions, $fields = ['*'])
    {
        $records = static::init()->where($conditions)->select(static::$table, $fields);
        return static::map(static::$type, (array)$records);
    }

    /**
     * find records by id
     *
     * @param $idOrCondition
     * @param string[] $fields
     * @return mixed
     * @throws Exception
     */
    public static function findOne($idOrCondition, array $fields = ['*']): mixed
    {
        $records = [];
        if (is_int($idOrCondition)) {
            $records = static::init()->where(['id' => $idOrCondition])->limit(1)->select(static::$table, $fields);
        } else {
            $records = static::init()->where($idOrCondition)->limit(1)->select(static::$table, $fields);
        }

        $records = static::map(static::$type, (array)$records);
        return count($records) > 0 ? $records[0] : $records;
    }


    /**
     * saves this record to the database.
     *
     * @return bool returns the status.
     * @throws Exception
     */
    public function create(): bool
    {
        $data = [];
        foreach (static::$attributes as $attr) {
            if (property_exists($this, $attr)) {
                $data[$attr] = $this->$attr;
            }
        }

        $this->id = static::init()->set($data)->insert(static::$table);
        return $this->id;
    }

    /**
     * updates this record.
     *
     * @return bool returns the status.
     * @throws Exception
     */
    public function save(): bool
    {
        $data = [];
        foreach (static::$attributes as $attr) {
            if (property_exists($this, $attr)) {
                $data[$attr] = $this->$attr;
            }
        }

        return static::init()->set($data)->where(['id' => $this->id])->update(static::$table);
    }

    /**
     * deletes this record from the database.
     *
     * @return bool returns the status.
     * @throws Exception
     */
    public function delete(): bool
    {
        return static::init()->where(['id' => $this->id])->delete(static::$table);
    }
}

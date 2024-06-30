<?php
/*
 * This file is NOT part of the Bot Web Handler project.
 *
 * It was originally created by:
 * author: David Carr (dave@dcblog.dev)
 * package: https://github.com/dcblogdev/pdo-wrapper
 *
 * and modified to fit in this project by:
 * author: Ismail Aatif (ismail@ismailian.com)
 * project: https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Database;

use PDO;
use Exception;
use TeleBot\System\ExceptionHandler;

class DbClient
{

    /** @var PDO $db PDO instance */
    protected PDO $db;

    /**
     * Array of connection arguments
     *
     * @throws Exception
     */
    public function __construct()
    {
        if (empty(getenv('DATABASE_NAME'))) {
            throw new Exception('Database name not defined');
        }

        if (empty(getenv('DATABASE_USER'))) {
            throw new Exception('Database username not defined');
        }

        $type = getenv('DATABASE_TYPE', true) ?? 'mysql';
        $host = getenv('DATABASE_HOST', true) ?? 'localhost';
        $port = getenv('DATABASE_PORT', true) ?? '3306';
        $username = getenv('DATABASE_USER', true) ?? '';
        $password = getenv('DATABASE_PASS', true) ?? '';
        $database = getenv('DATABASE_NAME', true);
        $charset = getenv('DATABASE_CHARSET', true) ?? 'utf8';

        $this->db = new PDO(
            "$type:host=$host;port=$port;dbname=$database;charset=$charset",
            $username, $password
        );

        $this->db->setAttribute(PDO::ATTR_PERSISTENT, true);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * get PDO instance
     *
     * @return PDO $db PDO instance
     */
    public function getClient(): PDO
    {
        return $this->db;
    }

    /**
     * execute raw sql query
     *
     * @param string $sql sql query
     * @return void
     */
    public function raw(string $sql): void
    {
        $this->db->query($sql);
    }

    /**
     * get an array of records
     *
     * @param string $sql sql query
     * @param array $args params
     * @param int $fetchMode set return mode ie object or array
     * @return array|bool returns array of records
     */
    public function rows(string $sql, array $args = [], int $fetchMode = PDO::FETCH_OBJ): array|bool
    {
        return $this->run($sql, $args)->fetchAll($fetchMode);
    }

    /**
     * execute sql query sql query with query parameters
     *
     * @param string $sql sql query
     * @param array $args query params
     * @return object returns a PDO object
     */
    public function run(string $sql, array $args = []): object
    {
        if (empty($args)) {
            return $this->db->query($sql);
        }

        $stmt = $this->db->prepare($sql);
        try {
            $is_assoc = !(array() === $args) && array_keys($args) !== range(0, count($args) - 1);
            if ($is_assoc) {
                foreach ($args as $key => $value) {
                    if (is_int($value)) {
                        $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":$key", $value);
                    }
                }

                $stmt->execute();
            } else {
                $stmt->execute($args);
            }
        } catch (\Exception $e) {
            ExceptionHandler::onException($e);
        }

        return $stmt;
    }

    /**
     * get an array of records
     *
     * @param string $sql sql query
     * @param array $args params
     * @param int $fetchMode set return mode ie object or array
     * @return array|object|bool returns single record
     */
    public function row(string $sql, array $args = [], int $fetchMode = PDO::FETCH_OBJ): array|object|bool
    {
        return $this->run($sql, $args)->fetch($fetchMode);
    }

    /**
     * get record by id
     *
     * @param string $table name of table
     * @param integer $id id of record
     * @param int $fetchMode set return mode ie object or array
     * @return array|object|bool returns single record
     */
    public function getById(string $table, int $id, int $fetchMode = PDO::FETCH_OBJ): array|object|bool
    {
        return $this->run("SELECT * FROM $table WHERE id = ?", [$id])->fetch($fetchMode);
    }

    /**
     * get number of records
     *
     * @param string $sql sql query
     * @param array $args params
     * @return int returns number of records
     */
    public function count(string $sql, array $args = []): int
    {
        return $this->run($sql, $args)->rowCount();
    }

    /**
     * insert record
     *
     * @param string $table table name
     * @param array $data array of columns and values
     */
    public function insert(string $table, array $data): int|string|bool
    {
        /** add columns into comma seperated string */
        $columns = implode(',', array_map(
                fn($k) => ('`' . trim($k, '`') . '`'),
                array_keys($data))
        );

        /** get values */
        $values = array_values($data);
        $placeholders = array_map(fn($val) => '?', array_keys($data));

        /** convert array into comma seperated string */
        $placeholders = join(',', array_values($placeholders));

        $this->run("INSERT INTO $table ($columns) VALUES ($placeholders)", $values);
        return $this->lastInsertId();
    }

    /**
     * get primary key of last inserted record
     *
     * @param bool $asInt cast ID to integer
     * @return int|string|bool
     */
    public function lastInsertId(bool $asInt = false): int|string|bool
    {
        if (is_int($lastId = $this->db->lastInsertId())) {
            return (int)$lastId;
        }

        return $lastId;
    }

    /**
     * update record
     *
     * @param string $table table name
     * @param array $data array of columns and values
     * @param array $where array of columns and values
     * @return int|bool returns number of affected rows, otherwise false.
     */
    public function update(string $table, array $data, array $where): int|bool
    {
        $values = array_values($data);
        $fieldDetails = array_map(fn($key) => "`$key` = ?,", array_keys($data));
        $fieldDetails = rtrim(join('', $fieldDetails), ',');

        /** setup where statement */
        $i = 0;
        $whereDetails = null;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "`$key` = ?" : " AND `$key` = ?";
            $values[] = $value;
            $i++;
        }

        $stmt = $this->run("UPDATE $table SET $fieldDetails WHERE $whereDetails", $values);
        return $stmt->rowCount();
    }

    /**
     * delete records
     *
     * @param string $table table name
     * @param array $where array of columns and values
     * @param integer $limit limit number of records
     * @return int|bool returns number of affected rows, otherwise false.
     */
    public function delete(string $table, array $where, int $limit = 1): int|bool
    {
        /** collect the values from collection */
        $values = array_values($where);

        /** setup where */
        $i = 0;
        $whereDetails = null;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }

        /** if limit is a number use a limit on the query */
        if (is_numeric($limit)) {
            $limit = "LIMIT $limit";
        }

        $stmt = $this->run("DELETE FROM $table WHERE $whereDetails $limit", $values);
        return $stmt->rowCount();
    }

    /**
     * delete all records
     *
     * @param string $table table name
     * @return int|bool returns number of affected rows, otherwise false.
     */
    public function deleteAll(string $table): int|bool
    {
        $stmt = $this->run("DELETE FROM $table");
        return $stmt->rowCount();
    }

    /**
     * delete record by id
     *
     * @param string $table table name
     * @param integer $id id of record
     * @return int|bool returns number of affected rows, otherwise false.
     */
    public function deleteById(string $table, int $id): int|bool
    {
        $stmt = $this->run("DELETE FROM $table WHERE id = ?", [$id]);
        return $stmt->rowCount();
    }

    /**
     * delete records by ids
     *
     * @param string $table table name
     * @param string|array $ids ids of records
     * @return int|bool returns number of affected rows, otherwise false.
     */
    public function deleteByIds(string $table, string|array $ids): int|bool
    {
        if (is_array($ids)) {
            $ids = "'" . implode("','", $ids) . "'";
        }

        $stmt = $this->run("DELETE FROM $table WHERE id IN ($ids)");
        return $stmt->rowCount();
    }

    /**
     * truncate table
     *
     * @param string $table table name
     * @return int|bool returns number of affected rows, otherwise false
     */
    public function truncate(string $table): int|bool
    {
        $stmt = $this->run("TRUNCATE TABLE $table");
        return $stmt->rowCount();
    }

}
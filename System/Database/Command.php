<?php

namespace TeleBot\System\Database;

use Exception;

trait Command
{

    /**
     * select fields from a table
     *
     * @param string $table
     * @param array $fields The fields to select.
     * @return object|array|bool returns this instance of database.
     * @throws Exception
     */
    public function select(string $table, array $fields = ['*']): object|array|bool
    {
        $this->_table = $table;
        $this->_command = 'SELECT';

        /** add [id] to the fields if not there and if fields is not set to global(*) */
        if (!in_array('*', $fields)) {
            if (!in_array('id', $fields)) {
                $fields[] = 'id';
            }
        }

        $this->_fields = $fields;

        return $this->query();
    }

    /**
     * insert new resource into the database.
     *
     * @param string $table The table to insert the resource into.
     * @return object|int|bool returns this instance of database.
     * @throws Exception
     */
    public function insert(string $table): object|int|bool
    {
        $this->_table = $table;
        $this->_command = 'INSERT';

        return $this->query();
    }

    /**
     * updates resource(s) on a table.
     *
     * @param string $table the table to update from.
     * @return bool returns this instance of database.
     * @throws Exception
     */
    public function update(string $table): bool
    {
        $this->_table = $table;
        $this->_command = 'UPDATE';

        return $this->query();
    }

    /**
     * deletes a resource(s) from database.
     *
     * @param string $table The table to delete from.
     * @return bool returns this instance of database.
     * @throws Exception
     */
    public function delete(String $table): bool
    {
        $this->_table = $table;
        $this->_command = 'DELETE';

        return $this->query();
    }

    /**
     * counts records on a table.
     * 
     * @return int return number of records on a table.
     */
    public function count(string $table): int
    {
        $result = $this->raw("SELECT count(*) FROM `$table`");

        return $result->fetch_array();
    }

    /**
     * truncates a table.
     * 
     * @param string $table table name to drop.
     * @return bool result of the query.
     */
    public function truncate(string $table): bool
    {
        $this->raw("TRUNCATE `$table`");

        return true;
    }
}

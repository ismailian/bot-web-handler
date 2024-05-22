<?php

namespace TeleBot\System\Database;

use Exception;
use PDO;

trait QueryBuilder
{

    /**
     * cleans up variables
     */
    private function empty(): void
    {
        $this->dbQuery = '';
        $this->_command = '';
        $this->_table = '';
        $this->_limit = 0;
        $this->_keys = [];
        $this->_values = [];
        $this->_fields = [];
        $this->_set = [];
        $this->_where = [];
    }

    /**
     * get value type.
     *
     * @param mixed $value the value to get the type of.
     */
    private function getType(mixed $value): int
    {
        return match (true) {
            is_int($value) => PDO::PARAM_INT,
            is_bool($value) => PDO::PARAM_BOOL,
            is_null($value) => PDO::PARAM_NULL,
            default => PDO::PARAM_STR,
        };
    }

    /**
     * builds the query before submitting it to the database.
     *
     * @return object|bool returns a prepared sql statement or false.
     */
    private function build(): object|bool
    {
        $sql = $this->_command;
        $sql .= match ($this->_command) {
            'SELECT' => ' ' . join(',', $this->_fields) . ' FROM ',
            'INSERT' => ' INTO ',
            'UPDATE' => ' ',
            'DELETE' => ' FROM ',
        };

        $sql .= "`{$this->_table}`";

        // if update/delete/select check for where clause
        if (in_array($this->_command, ['SELECT', 'UPDATE', 'DELETE'])) {
            if (($this->_command !== 'SELECT') && count($this->_set) > 0) {
                $raw = [];
                $keys = array_keys($this->_set);
                $values = array_values($this->_set);
                foreach ($this->_set as $key => $value) {
                    $raw[] = "`$key`=:" . $key;
                }

                $sql .= ' SET ' . join(',', $raw);
                $this->_keys = [...$this->_keys, ...$keys];
                $this->_values = [...$this->_values, ...$values];
            }

            if (count($this->_where) > 0) {
                $raw = [];
                $sql .= ' WHERE ';
                $keys = array_keys($this->_where);
                $values = array_values($this->_where);
                foreach ($this->_where as $key => $value) {
                    $raw[] = "`$key`=:" . $key;
                }

                $sql .= join(' AND ', $raw);
                $this->_keys = [...$this->_keys, ...$keys];
                $this->_values = [...$this->_values, ...$values];
            }
        }

        // if insert check for set clause
        if ($this->_command === 'INSERT') {
            if (count($this->_set) > 0) {
                $keys = array_keys($this->_set);
                $values = array_values($this->_set);
                $sql .= " (" . implode(', ', $keys) . ") ";
                for ($i = 0; $i < count($values); $i++) {
                    $values[$i] = ":" . $keys[$i];
                }

                $sql .= "VALUES (" . implode(', ', $values) . ")";
                $this->_keys = [...$this->_keys, ...$keys];
                $this->_values = [...$this->_values, ...array_values($this->_set)];
            }
        }

        // order_by and limit only apply to select command
        if ($this->_command === 'SELECT') {
            if (count($this->_orderBy) > 0) {
                $field = $this->_orderBy['field'];
                $value = $this->_orderBy['value'];
                $sql .= " ORDER BY `$field` $value";
            }

            if ($this->_limit > 0) {
                $sql .= ' LIMIT ' . $this->_limit;
            }
        }

        // preparing statement
        $this->dbQuery = $sql;
        $bindableParams = count($this->_keys) > 0 && count($this->_values);
        if ($bindableParams) {
            $dbStatement = $this->dbResource->prepare($this->dbQuery);
            if ($dbStatement !== false) {
                for ($i = 0; $i < count($this->_keys); $i++) {
                    $key = $this->_keys[$i];
                    $value = $this->_values[$i];
                    $dbStatement->bindValue(':' . $key, $value, $this->getType($value));
                }

                return $dbStatement;
            }
        }

        return false;
    }

    /**
     * submits a query to the database.
     *
     * @param string|null $query The query to submit.
     * @return mixed
     * @throws Exception
     */
    public function query(string $query = null): mixed
    {
        $dbStatement = $this->build();
        if ($dbStatement === false) {
            $payload = $this->dbResource->query($this->dbQuery);
            return (is_object($payload) ? $payload->fetchAll() : $payload);
        }

        $dbStatement->execute();
        $errorInfo = $dbStatement->errorInfo();
        if ($errorInfo && !is_null($errorInfo[2])) {
            $this->empty();
            throw new Exception($errorInfo[2], $errorInfo[1]);
        }

        if (is_bool($dbStatement)) {
            $this->empty();
            return true;
        }

        if (is_object($dbStatement)) {
            $this->_error = $dbStatement->errorInfo ?? null;
            if ($this->_command === 'INSERT') {
                $this->empty();
                return $this->dbResource->lastInsertId();
            }

            if (in_array($this->_command, ['UPDATE', 'DELETE'])) {
                $this->empty();
                return ($dbStatement->rowCount() > 0);
            }

            else {
                $this->empty();
                return $dbStatement->fetchAll();
            }
        }

        $this->empty();
        return false;
    }

    /**
     * Run a query directly and return result.
     */
    public function raw(string $query)
    {
        return $this->dbResource->query($query);
    }

}
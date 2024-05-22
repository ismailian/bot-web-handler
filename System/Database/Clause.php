<?php

namespace TeleBot\System\Database;

trait Clause
{

    /**
     * add a key:value pair for the set clause.
     *
     * @param array $fieldValuePair array containing key:value pair for a set clause.
     * @return object returns this database instance object.
     */
    public function set(array $fieldValuePair): object
    {
        foreach ($fieldValuePair as $key => $value) {
            $this->_set[$key] = $value;
        }

        return $this;
    }

    /**
     * add a key:value pair for the where clause.
     *
     * @param array $fieldValuePair array containing key:value pair for a where clause.
     * @return object returns this database instance object.
     */
    public function where(array $fieldValuePair): object
    {
        foreach ($fieldValuePair as $key => $value) {
            $this->_where[$key] = $value;
        }

        return $this;
    }

    /**
     * add a limit clause.
     *
     * @param int $limit the limit number.
     * @return object returns this database instance object.
     */
    public function limit(int $limit): object
    {
        $this->_limit = $limit;
        return $this;
    }

    /**
     * add an order by clause.
     *
     * @param string $field array containing key:value pair for a where clause.
     * @param string $value value to order by [ASC, DESC].
     * @return object returns this database instance object.
     */
    public function orderBy(string $field, string $value = 'ASC'): object
    {
        if (in_array($value, ['ASC', 'DESC'])) {
            $this->_orderBy = [
                'field' => $field,
                'value' => $value
            ];
        }

        return $this;
    }
}

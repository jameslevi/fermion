<?php

namespace Graphite\Component\Fermion;

abstract class BaseBuilder
{
    /**
     * Name of the table.
     * 
     * @var string
     */
    protected $tablename;

    /**
     * Placeholder value index counter.
     * 
     * @var int
     */
    private $index = 0;

    /**
     * Store placeholder values.
     * 
     * @var array
     */
    private $placeholders = array();

    /**
     * Return all placeholder values.
     * 
     * @return  array
     */
    public function values()
    {
        return $this->placeholders;
    }

    /**
     * Return the generated SQL.
     * 
     * @return  string
     */
    public function sql()
    {
        return $this->generate();
    }

    /**
     * Generate SQL query.
     * 
     * @return  string
     */
    abstract protected function generate();

    /**
     * Return the name of the table.
     * 
     * @return  string
     */
    public function getTable()
    {
        return $this->tablename;
    }

    /**
     * Factory for creating placeholder.
     * 
     * @param   mixed $value
     * @return  string
     */
    protected function registerPlaceholder($value)
    {
        $key = 'v' . $this->index;

        $this->placeholders[$key] = filter_var($value, FILTER_SANITIZE_STRING);
        $this->index++;

        return ':' . $key;
    }
}
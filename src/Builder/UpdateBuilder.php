<?php

namespace Graphite\Component\Fermion\Builder;

use Graphite\Component\Fermion\FilterBuilder;

class UpdateBuilder extends FilterBuilder
{
    /**
     * Store all changes to be done.
     * 
     * @var array
     */
    private $data = array();

    /**
     * Construct a new update builder instance.
     * 
     * @param   string $tablename
     * @param   array $arguments
     * @return  void
     */
    public function __construct(string $tablename, ?array $arguments = null)
    {
        $this->tablename = $tablename;

        if(!is_null($arguments))
        {
            foreach($arguments as $column => $value)
            {
                $this->set($column, $value);
            }
        }
    }

    /**
     * Update a value by column.
     * 
     * @param   string $column
     * @param   mixed $value
     * @return  $this
     */
    public function set(string $column, $value)
    {
        if(!str_contains($column, '.'))
        {
            $column = $this->tablename . '.' . $column;
        }

        $this->data[$column] = $this->registerPlaceholder($value);

        return $this;
    }

    /**
     * Generate SQL update query.
     * 
     * @return  string
     */
    protected function generate()
    {
        $sql        = 'UPDATE ' . $this->tablename . ' SET';
        $join       = $this->getJoinStatements();
        $where      = $this->getWhereStatements();

        if(!is_null($this->data))
        {
            foreach($this->data as $column => $value)
            {
                $sql .= ' ' . $column . ' = ' . $value;
            }
        }

        if(!is_null($join))
        {
            $sql .= $join;
        }

        if(!is_null($where))
        {
            $sql .= $where;
        }

        return $sql;
    }
}
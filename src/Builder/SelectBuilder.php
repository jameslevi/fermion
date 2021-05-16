<?php

namespace Graphite\Component\Fermion\Builder;

use Graphite\Component\Fermion\FilterBuilder;

class SelectBuilder extends FilterBuilder
{
    /**
     * Store columns to return for this query.
     * 
     * @var array
     */
    private $arguments = array();

    /**
     * Return only distinct values from the query.
     * 
     * @var bool
     */
    private $distinct = false;

    /**
     * Store groups of columns.
     * 
     * @var array
     */
    private $groups = array();

    /**
     * Sorting order of the results.
     * 
     * @var string
     */
    private $order = 'asc';

    /**
     * Store result order data.
     * 
     * @var array
     */
    private $orders = array();

    /**
     * Limit start.
     * 
     * @var int
     */
    private $start;

    /**
     * Limit offset.
     * 
     * @var int
     */
    private $offset;

    /**
     * Construct a new select query builder.
     * 
     * @param   string $tablename
     * @param   mixed $args
     * @return  void
     */
    public function __construct(string $tablename, $args)
    {
        $this->tablename = $tablename;
        $this->setArguments($args);
    }

    /**
     * Set columns to return for this query.
     * 
     * @param   mixed $args
     * @return  void
     */
    private function setArguments($args)
    {
        if($args != '*')
        {
            if(is_array($args))
            {
                foreach($args as $key => $arg)
                {
                    $this->get($arg, (!is_int($key) ? $key : null));
                }
            }
            else if(is_string($args))
            {
                foreach(explode(', ', trim($args)) as $arg)
                {
                    if(str_starts_with($arg, ' '))
                    {
                        $arg = str_move($arg, 1);
                    }

                    $this->get($arg);
                }
            }
        }
    }

    /**
     * Add column to return from query.
     * 
     * @param   string $key
     * @param   string $alias
     * @return  $this
     */
    public function get(string $key, ?string $alias = null)
    {
        if(!str_contains($key, '.'))
        {
            $this->arguments[] = !is_null($alias) ?  ($key . ' AS ' . $alias) : ($this->tablename . '.' . $key);
        }
        else
        {
            $this->arguments[] = $key . (!is_null($alias) ? ' AS ' . $alias : '');
        }

        return $this;
    }

    /**
     * Enable distinct values.
     * 
     * @return  $this
     */
    public function distinct()
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * Make select functions.
     * 
     * @param   string $column
     * @param   string $func
     * @return  $this
     */
    private function makeFunc(string $column, string $func, ?string $alias = null)
    {
        if(!str_contains($column, '.'))
        {
            $column = $this->tablename . '.' . $column;
        }

        $this->arguments[] = strtoupper($func) . '(' . $column . ')' . (!is_null($alias) ? (' AS ' . $alias) : '');

        return $this;
    }
    
    /**
     * Returns the largest value of the selected column.
     * 
     * @param   string $column
     * @param   string $alias
     * @return  $this
     */
    public function max(string $column, ?string $alias = null)
    {
        return $this->makeFunc($column, 'MAX', $alias);
    }

    /**
     * Returns the lowest value of the selected column.
     * 
     * @param   string $column
     * @param   string $alias
     * @return  $this
     */
    public function min(string $column, ?string $alias = null)
    {
        return $this->makeFunc($column, 'MIN', $alias);
    }

    /**
     * Returns the number of rows that matches a specified criteria.
     * 
     * @param   string $column
     * @param   string $alias
     * @return  $this
     */
    public function count(string $column, ?string $alias = null)
    {
        return $this->makeFunc($column, 'COUNT', $alias);
    }

    /**
     * Returns the average value of a numeric column.
     * 
     * @param   string $column
     * @param   string $alias
     * @return  $this
     */
    public function avg(string $column, ?string $alias = null)
    {
        return $this->makeFunc($column, 'AVG', $alias);
    }

    /**
     * Returns the total sum of a numeric value.
     * 
     * @param   string $column
     * @param   string $alias
     * @return  $this
     */
    public function sum(string $column, ?string $alias = null)
    {
        return $this->makeFunc($column, 'SUM', $alias);
    }

    /**
     * Group rows that have the same values.
     * 
     * @param   mixed $columns
     * @return  $this
     */
    public function groupBy($columns)
    {
        if(is_array($columns))
        {
            foreach($columns as $column)
            {
                if(!str_contains($column, '.'))
                {
                    $this->groups[] = $this->tablename . '.' . $column;
                }
                else
                {
                    $this->groups[] = $column;
                }
            }
        }
        else
        {
            if(!str_contains($columns, '.'))
            {
                $this->groups[] = $this->tablename . '.' . $columns;
            }
            else
            {
                $this->groups[] = $columns;
            }
        }

        return $this;
    }

    /**
     * Order results by columns in ascending or descending order.
     * 
     * @param   mixed $columns
     * @param   string $order
     * @return  $this
     */
    public function orderBy($columns, string $order = 'asc')
    {
        if(is_array($columns))
        {
            foreach($columns as $column)
            {
                if(!str_contains($column, '.'))
                {
                    $this->orders[] = $this->tablename . '.' . $column;
                }
                else
                {
                    $this->orders[] = $column;
                }
            }
        }
        else if(is_string($columns))
        {
            foreach(explode(', ', trim($columns)) as $column)
            {
                if(str_starts_with($column, ' '))
                {
                    $column = str_move($column, 1);
                }

                if(!str_contains($column, '.'))
                {
                    $this->orders[] = $this->tablename . '.' . $column;
                }
                else
                {
                    $this->orders[] = $column;
                }
            }
        }
        
        $this->order = $order;

        return $this;
    }

    /**
     * Order results by ascending order.
     * 
     * @param   string $column
     * @return  $this
     */
    public function asc(string $column)
    {
        return $this->orderBy($column, 'asc');
    }

    /**
     * Order results by descending order.
     * 
     * @param   string $column
     * @return  $this
     */
    public function desc(string $column)
    {
        return $this->orderBy($column, 'desc');
    }

    /**
     * Sort results in random order.
     * 
     * @return  $this
     */
    public function rand()
    {
        $this->order = 'rand';

        return $this;
    }

    /**
     * Set result limits.
     * 
     * @param   int $start
     * @param   int $offset
     * @return  $this
     */
    public function limit(int $start, int $offset)
    {
        $this->start = $start;
        $this->offset = $offset;

        return $this;
    }

    /**
     * Generate SQL select query.
     * 
     * @return  string
     */
    protected function generate()
    {
        if(empty($this->arguments))
        {
            $this->arguments[] = $this->tablename . '.*';
        }

        $sql        = 'SELECT' . ($this->distinct ? ' DISTINCT' : '') . ' ' . implode(', ', $this->arguments) . ' FROM `' . $this->tablename . '`';
        $join       = $this->getJoinStatements();
        $where      = $this->getWhereStatements();
        
        if(!is_null($join))
        {
            $sql .= $join;
        }

        if(!empty($this->groups))
        {
            $sql .= ' GROUP BY ' . implode(', ', $this->groups);
        }

        if(!is_null($where))
        {
            $sql .= $where;
        }

        if($this->order != 'rand')
        {
            if(!empty($this->orders))
            {
                $sql .= ' ORDER BY ' . implode(', ', $this->orders) . ' ' . strtoupper($this->order);
            }
        }
        else
        {
            $sql .= ' ORDER BY RAND()';
        }

        if(!is_null($this->start) && !is_null($this->offset))
        {
            $sql .= ' LIMIT ' . $this->start . ', ' . $this->offset;
        }

        if(str_ends_with($sql, ' '))
        {
            $sql = str_move_right($sql, 1);
        }

        return $sql;
    }
}
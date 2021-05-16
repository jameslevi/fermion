<?php

namespace Graphite\Component\Fermion;

abstract class JoinBuilder extends BaseBuilder
{
    /**
     * Store join statement data.
     * 
     * @var array
     */
    private $joins = array();

    /**
     * Generate join statements.
     * 
     * @return  mixed
     */
    protected function getJoinStatements()
    {
        return !empty($this->joins) ? (' ' . implode(' ', $this->joins)) : null;
    }

    /**
     * Factory for creating new join statement.
     * 
     * @param   string $type
     * @param   string $tablename
     * @param   string $column1
     * @param   string $column2
     * @return  $this
     */
    private function makeJoin(string $type, string $tablename, string $column1, string $column2)
    {
        $this->joins[] = strtoupper($type) . ' JOIN ' . $tablename . ' ON ' . $this->tablename . '.' . $column1 . ' = ' . $tablename . '.' . $column2;

        return $this;
    }

    /**
     * Add new inner join statement.
     * 
     * @param   string $tablename
     * @param   string $column1
     * @param   string $column2
     * @return  $this
     */
    public function innerJoin(string $tablename, string $column1, string $column2)
    {
        return $this->makeJoin('inner', $tablename, $column1, $column2);
    }

    /**
     * Add new left join statement.
     * 
     * @param   string $tablename
     * @param   string $column1
     * @param   string $column2
     * @return  $this
     */
    public function leftJoin(string $tablename, string $column1, string $column2)
    {
        return $this->makeJoin('left', $tablename, $column1, $column2);
    }

    /**
     * Add new right join statement.
     * 
     * @param   string $tablename
     * @param   string $column1
     * @param   string $column2
     * @return  $this
     */
    public function rightJoin(string $tablename, string $column1, string $column2)
    {
        return $this->makeJoin('right', $tablename, $column1, $column2);
    }

    /**
     * Add new full outer join statement.
     * 
     * @param   string $tablename
     * @param   string $column1
     * @param   string $column2
     * @return  $this
     */
    public function outerJoin(string $tablename, string $column1, string $column2)
    {
        return $this->makeJoin('full outer', $tablename, $column1, $column2);
    }
}
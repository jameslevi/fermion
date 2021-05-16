<?php

namespace Graphite\Component\Fermion\Builder;

use Graphite\Component\Fermion\FilterBuilder;

class DeleteBuilder extends FilterBuilder
{
    /**
     * Construct a new delete builder instance.
     * 
     * @param   string $tablename
     * @return  void
     */
    public function __construct(string $tablename)
    {
        $this->tablename = $tablename;
    }

    /**
     * Generate SQL delete query.
     * 
     * @return  string
     */
    protected function generate()
    {
        $sql        = 'DELETE FROM ' . $this->tablename;
        $join       = $this->getJoinStatements();
        $where      = $this->getWhereStatements();

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
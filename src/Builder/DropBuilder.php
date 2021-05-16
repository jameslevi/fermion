<?php

namespace Graphite\Component\Fermion\Builder;

use Graphite\Component\Fermion\BaseBuilder;

class DropBuilder extends BaseBuilder
{
    /**
     * Construct a new drop table builder instance.
     * 
     * @param   string $tablename
     * @return  void
     */
    public function __construct(string $tablename)
    {
        $this->tablename = $tablename;
    }

    /**
     * Generate a new drop table SQL query.
     * 
     * @return  string
     */
    protected function generate()
    {
        return 'DROP TABLE ' . $this->tablename;
    }
}
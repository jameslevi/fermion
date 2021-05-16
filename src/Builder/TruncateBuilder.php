<?php

namespace Graphite\Component\Fermion\Builder;

use Graphite\Component\Fermion\BaseBuilder;

class TruncateBuilder extends BaseBuilder
{
    /**
     * Construct a new truncate query builder.
     * 
     * @param   string $tablename
     * @return  void
     */
    public function __construct(string $tablename)
    {
        $this->tablename = $tablename;
    }

    /**
     * Generate a new truncate SQL query.
     * 
     * @return  string
     */
    protected function generate()
    {
        return 'TRUNCATE TABLE ' . $this->tablename;
    }
}
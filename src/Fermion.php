<?php

namespace Graphite\Component\Fermion;

use Graphite\Component\Fermion\Builder\DeleteBuilder;
use Graphite\Component\Fermion\Builder\DropBuilder;
use Graphite\Component\Fermion\Builder\InsertBuilder;
use Graphite\Component\Fermion\Builder\SelectBuilder;
use Graphite\Component\Fermion\Builder\TruncateBuilder;
use Graphite\Component\Fermion\Builder\UpdateBuilder;

class Fermion
{
    /**
     * Name of the table from the database.
     * 
     * @var string
     */
    private $tablename;

    /**
     * Construct a new fermion instance.
     * 
     * @param   string $tablename
     * @return  void
     */
    public function __construct(string $tablename)
    {
        $this->tablename = $tablename;
    }

    /**
     * Build a new MySQL select query.
     * 
     * @param   mixed $args
     * @return  \Graphite\Component\Fermion\Builder\SelectBuilder
     */
    public function select($args = '*')
    {
        return new SelectBuilder($this->tablename, $args);
    }

    /**
     * Build new MySQL update query.
     * 
     * @param   array $args
     * @return  \Graphite\Component\Fermion\Builder\UpdateBuilder
     */
    public function update(array $args = null)
    {
        return new UpdateBuilder($this->tablename, $args);
    }

    /**
     * Build a new MySQL delete query.
     * 
     * @param   array $args
     * @return  \Graphite\Component\Fermion\Builder\DeleteBuilder
     */
    public function delete()
    {
        return new DeleteBuilder($this->tablename);
    }

    /**
     * Build a new MySQL insert query.
     * 
     * @param   array $data
     * @return  \Graphite\Component\Fermion\Builder\InsertBuilder
     */
    public function insert(array $data)
    {
        return new InsertBuilder($this->tablename, $data);
    }

    /**
     * Build a new MySQL truncate table query.
     * 
     * @return  \Graphite\Component\Fermion\Builder\TruncateBuilder
     */
    public function truncate()
    {
        return new TruncateBuilder($this->tablename);
    }

    /**
     * Build a new MySQL drop table query.
     * 
     * @return  \Graphite\Component\Fermion\Builder\DropBuilder
     */
    public function drop()
    {
        return new DropBuilder($this->tablename);
    }

    /**
     * Return the current version.
     * 
     * @return  string
     */
    public static function version()
    {
        return 'Fermion version 1.0.0';
    }
}
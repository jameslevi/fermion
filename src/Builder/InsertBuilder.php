<?php

namespace Graphite\Component\Fermion\Builder;

use Graphite\Component\Fermion\BaseBuilder;

class InsertBuilder extends BaseBuilder
{
    /**
     * Store data to insert.
     * 
     * @var array
     */
    private $data = array();

    /**
     * Construct insert builder instance.
     * 
     * @param   string $tablename
     * @param   array $data
     * @return  void
     */
    public function __construct(string $tablename, array $data)
    {
        $this->tablename    = $tablename;
        $this->data         = $data;
    }

    /**
     * Generate SQL query builder.
     * 
     * @return  string
     */
    protected function generate()
    {
        $sql    = 'INSERT INTO ' . $this->tablename . ' (';
        $keys = array();

        foreach($this->data as $key => $data)
        {
            if(!str_contains($key, '.'))
            {
                $keys[] = $this->tablename . '.' . $key;
            }
            else
            {
                $keys[] = $data;
            }
        }

        $sql .= implode(', ', $keys) . ') VALUES(';
        $values = array();

        foreach($this->data as $data)
        {
            $values[] = $this->registerPlaceholder($data);
        }

        $sql .= implode(', ', $values);

        return $sql . ')';
    }
}
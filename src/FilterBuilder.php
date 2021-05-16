<?php

namespace Graphite\Component\Fermion;

abstract class FilterBuilder extends JoinBuilder
{
    /**
     * Store where statement data.
     * 
     * @var array
     */
    private $where = array();

    /**
     * Generate and return where statements.
     * 
     * @return  string
     */
    protected function getWhereStatements()
    {
        if(!empty($this->where))
        {
            $sql = '';

            foreach($this->where as $statement)
            {
                $sql .= $statement . ' ';
            }

            if(str_starts_with($sql, 'AND '))
            {
                $sql = str_move($sql, 4);
            }

            if(str_starts_with($sql, 'OR '))
            {
                $sql = str_move($sql, 3);
            }

            $sql = str_replace(' )', ')', $sql);
            $sql = str_replace('( ', '(', $sql);
            $sql = str_replace('(AND ', '(', $sql);
            $sql = str_replace('(OR', '(', $sql);

            return ' WHERE ' . trim($sql);
        }
    }

    /**
     * Prepend table name at the beginning of field names.
     * 
     * @param   string $field
     * @return  string
     */
    private function formatFieldName(string $field)
    {
        if(!str_contains($field, '.'))
        {
            $field = $this->tablename . '.' . $field;
        }

        return $field;
    }

    /**
     * Factory for creating conditional statements.
     * 
     * @param   string $field
     * @param   string $operator
     * @param   string $glue
     * @return  $this
     */
    private function statementFactory(string $field, $value, string $operator, string $glue = 'AND')
    {
        if(str_starts_with($field, ' '))
        {
            $field = str_move($field, 1);
        }

        return $this->raw(strtoupper($glue) . ' ' . $this->formatFieldName($field) . ' ' . $operator . ' ' . $this->registerPlaceholder($value));
    }

    /**
     * Create new raw statement.
     * 
     * @param   string $statement
     * @return  $this
     */
    public function raw(string $statement)
    {
        $this->where[] = $statement;

        return $this;
    }

    /**
     * Create new OR conditional operators.
     * 
     * @return  $this
     */
    public function or()
    {
        return $this->raw('OR');
    }

    /**
     * Create new AND conditional operators.
     * 
     * @return  $this
     */
    public function and()
    {
        return $this->raw('AND');
    }

    /**
     * Start where conditional statement group.
     * 
     * @return  $this
     */
    public function startGroup()
    {
        return $this->raw('(');
    }

    /**
     * End where conditional statement group.
     * 
     * @return  $this
     */
    public function endGroup()
    {
        return $this->raw(')');
    }

    /**
     * Factory for creating generic conditional statements.
     * 
     * @param   string $field
     * @param   string $operator
     * @param   mixed $value
     * @return  $this
     */
    public function where(string $field, string $operator, $value)
    {
        return $this->statementFactory($field, $value, $operator);
    }

    /**
     * Factory for creating generic conditional statements.
     * 
     * @param   string $field
     * @param   string $operator
     * @param   mixed $value
     * @return  $this
     */
    public function orWhere(string $field, string $operator, $value)
    {
        return $this->statementFactory($field, $value, $operator, 'OR');
    }

    /**
     * Create AND equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function equal(string $field, $value)
    {
        return $this->where($field, '=', $value);
    }

    /**
     * Create OR equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this 
     */
    public function orEqual(string $field, $value)
    {
        return $this->orWhere($field, '=', $value);
    }

    /**
     * Create AND not equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function notEqual(string $field, $value)
    {
        return $this->where($field, '!=', $value);
    }

    /**
     * Create OR not equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orNotEqual(string $field, $value)
    {
        return $this->orWhere($field, '!=', $value);
    }

    /**
     * Create AND less than conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function lessThan(string $field, $value)
    {
        return $this->where($field, '<', $value);
    }

    /**
     * Create OR less than equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orLessThan(string $field, $value)
    {
        return $this->orWhere($field, '<', $value);
    }

    /**
     * Create AND greater than conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function greaterThan(string $field, $value)
    {
        return $this->where($field, '>', $value);
    }

    /**
     * Create OR greater than conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orGreaterThan(string $field, $value)
    {
        return $this->orWhere($field, '>', $value);
    }

    /**
     * Create AND less than equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function lessThanEqual(string $field, $value)
    {
        return $this->where($field, '<=', $value);
    }

    /**
     * Create OR less than equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orLessThanEqual(string $field, $value)
    {
        return $this->orWhere($field, '<=', $value);
    }

    /**
     * Create AND greater than equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function greaterThanEqual(string $field, $value)
    {
        return $this->where($field, '>=', $value);
    }

    /**
     * Create AND greater than equal conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orGreaterThanEqual(string $field, $value)
    {
        return $this->orWhere($field, '>=', $value);
    }

    /**
     * Factory for creating in between conditional statement.
     * 
     * @param   string $field
     * @param   int $min
     * @param   int $max
     * @param   string $field
     * @return  $this
     */
    private function inBetweenFactory(string $field, int $min, int $max, string $glue = 'AND')
    {
        return $this->raw(strtoupper($glue) . ' ' . $this->tablename . '.' . $field . ' BETWEEN ' . $min . ' AND ' . $max);
    }

    /**
     * Create AND in between conditional statement.
     * 
     * @param   string $field
     * @param   int $min
     * @param   int $max
     * @return  $this
     */
    public function inBetween(string $field, int $min, int $max)
    {
        return $this->inBetweenFactory($field, $min, $max);
    }

    /**
     * Create OR in between conditional statement.
     * 
     * @param   string $field
     * @param   int $min
     * @param   int $max
     * @return  $this
     */
    public function orInBetween(string $field, int $min, int $max)
    {
        return $this->inBetweenFactory($field, $min, $max, 'OR');
    }

    /**
     * Create empty conditional statement.
     * 
     * @param   string $field
     * @return  $this
     */
    public function empty(string $field)
    {
        return $this->where($field, '=', '');
    }

    /**
     * Create OR empty conditional statement.
     * 
     * @param   string $field
     * @return  $this
     */
    public function orEmpty(string $field)
    {
        return $this->orWhere($field, '=', '');
    }

    /**
     * Create not empty conditional statement.
     * 
     * @param   string $field
     * @return  $this
     */
    public function notEmpty(string $field)
    {
        return $this->where($field, '!=', '');
    }

    /**
     * Create OR not empty conditional statement.
     * 
     * @param   string $field
     * @return  $this
     */
    public function orNotEmpty(string $field)
    {
        return $this->orWhere($field, '!=', '');
    }

    /**
     * Factory for creating null conditional statement.
     * 
     * @param   string $field
     * @param   string $glue
     * @return  $this
     */
    private function isNullFactory(string $field, string $glue = 'AND')
    {
        return $this->raw(strtoupper($glue) . ' ' . $this->formatFieldName($field) . ' IS NULL');
    }

    /**
     * Create null conditional statement.
     * 
     * @param   string $field
     * @return  $this
     */
    public function isNull(string $field)
    {
        return $this->isNullFactory($field);
    }

    /**
     * Create OR null conditional statement.
     * 
     * @param   string $field
     * @return  $this
     */
    public function orIsNull(string $field)
    {
        return $this->isNullFactory($field, 'OR');
    }

    /**
     * Factory for creating not null conditional statement.
     * 
     * @param   string $field
     * @param   string $glue
     * @return  $this
     */
    private function notNullFactory(string $field, string $glue = 'AND')
    {
        return $this->raw(strtoupper($glue) . ' ' . $this->formatFieldName($field) . ' IS NOT NULL');
    }

    /**
     * Create not null conditional statement.
     * 
     * @param   string $field
     * @param   string $glue
     * @return  $this
     */
    public function notNull(string $field)
    {
        return $this->notNullFactory($field);
    }

    /**
     * Create OR not null conditional statement.
     * 
     * @param   string $field
     * @return  $this
     */
    public function orNotNull(string $field)
    {
        return $this->notNullFactory($field, 'OR');
    }

    /**
     * Factory for creating like conditional statements.
     * 
     * @param   string $field
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */
    private function likeFactory(string $field, $value, string $glue = 'AND')
    {
        return $this->raw(strtoupper($glue) . ' ' . $this->formatFieldName($field) . ' LIKE ' . $this->registerPlaceholder($value));
    }

    /**
     * Create a new like conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function like(string $field, $value)
    {
        return $this->likeFactory($field, $value);
    }

    /**
     * Factory for creating not like conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */
    private function notLikeFactory(string $field, $value, string $glue = 'AND')
    {
        return $this->raw(strtoupper($glue) . ' ' . $this->formatFieldName($field) . ' NOT LIKE ' . $this->registerPlaceholder($value));
    }

    /**
     * Create a new not like conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function notLike(string $field, $value)
    {
        return $this->notLikeFactory($field, $value);
    }

    /**
     * Create a new OR like conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orLike(string $field, $value)
    {
        return $this->likeFactory($field, $value, 'OR');
    }

    /**
     * Create a new OR not like conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orNotLike(string $field, $value)
    {
        return $this->notLikeFactory($field, $value, 'OR');
    }

    /**
     * Create a new starts with conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function startsWith(string $field, $value)
    {
        return $this->like($field, $value . '%');
    }

    /**
     * Create a new ends with conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function endsWith(string $field, $value)
    {
        return $this->like($field, '%' . $value);
    }

    /**
     * Create a new OR starts with conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orStartsWith(string $field, $value)
    {
        return $this->orLike($field, $value . '%');
    }

    /**
     * Create a new OR ends with conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orEndsWith(string $field, $value)
    {
        return $this->orLike($field, '%' . $value);
    }

    /**
     * Create a new not starts with conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function notStartsWith(string $field, $value)
    {
        return $this->notLike($field, $value . '%');
    }

    /**
     * Create a new not ends with conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function notEndsWith(string $field, $value)
    {
        return $this->notLike($field, $value, '%' . $value);
    }

    /**
     * Create a new or not starts with conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orNotStartsWith(string $field, $value)
    {
        return $this->orNotLike($field, $value . '%');
    }

    /**
     * Create a new or not ends with conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orNotEndsWIth(string $field, $value)
    {
        return $this->orNotLike($field, '%' . $value);
    }

    /**
     * Create a new contain conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function contain(string $field, $value)
    {
        return $this->like($field, '%' . $value . '%');
    }

    /**
     * Create a new not contain conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function notContain(string $field, $value)
    {
        return $this->notLike($field, '%' . $value . '%');
    }

    /**
     * Create a new OR contain conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orContain(string $field, $value)
    {
        return $this->orLike($field, '%' . $value . '%');
    }

    /**
     * Create a new OR not contain conditional statement.
     * 
     * @param   string $field
     * @param   mixed $value
     * @return  $this
     */
    public function orNotContain(string $field, $value)
    {
        return $this->orNotLike($field, '%' . $value . '%');
    }

    /**
     * Factory for creating new where in conditional statement.
     * 
     * @param   string $field
     * @param   array $values
     * @param   string $glue
     * @return  $this
     */
    private function inFactory(string $field, array $values, string $glue = 'AND')
    {
        $where = strtoupper($glue) . ' ' . $this->formatFieldName($field) . ' IN (';
        $placeholders = array();

        foreach($values as $value)
        {
            $placeholders[] = $this->registerPlaceholder($value);
        }

        $where .= implode(', ', $placeholders);

        return $this->raw($where . ')');
    }
    
    /**
     * Create a new where in conditional statement.
     * 
     * @param   string $field
     * @param   array $values
     * @return  $this
     */
    public function in(string $field, array $values)
    {
        return $this->inFactory($field, $values);
    }

    /**
     * Create a new OR where in conditional statement.
     * 
     * @param   string $field
     * @param   array $values
     * @return  $this
     */
    public function orIn(string $field, array $values)
    {
        return $this->inFactory($field, $values, 'OR');
    }
}
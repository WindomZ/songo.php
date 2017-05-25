<?php declare(strict_types=1);

namespace Songo;

class QueryValue
{
    /**
     * @var string
     */
    protected $operator;


    /**
     * @var mixed|object|string
     */
    protected $value;


    public function __construct(string $str)
    {
        if (!$this->split($str)) {
            $this->value = null;
        }
    }

    protected function valid(): bool
    {
        return !empty($this->value);
    }


    protected function split(string $str): bool
    {
        if (strpos($str, '$') === 0) {
            $str = substr($str, 1);
        }

        $strs = explode('$', $str);
        if (count($strs) <= 1) {
            return false;
        }

        $this->operator = '$'.trim($strs[0]);
        if (!Operator::verifyQueryOperator($this->operator)) {
            return false;
        }

        if (count($strs) == 2) {
            $this->value = trim($strs[1]);

            return true;
        }

        $value = new QueryValue(implode('$', array_slice($strs, 1)));

        return $value->valid();
    }

    public function next(): QueryValue
    {
        if ($this->valid()) {
            if ($this->value instanceof QueryValue) {
                return $this->value;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getQuery() {

    }

    public function string(): string
    {
        if ($this->valid()) {
            $v = $this->next();
            if (isset($v) && !empty($v)) {
                return $this->operator.$v->string();
            }

            return $this->operator.$this->value;
        }

        return '';
    }
}

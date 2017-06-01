<?php declare(strict_types=1);

namespace Songo;

class QueryValues
{
    /**
     * @var array
     */
    protected $operators = array();

    /**
     * @var string
     */
    protected $value;

    public function __construct(QueryValue $value)
    {
        if (!empty($value)) {
            for ($v = $value; !empty($v); $v = $v->next()) {
                array_push($this->operators, $v->getOperator());
                $this->value = $v->getValue();
            }
        }
    }

    /**
     * @return array
     */
    public function getOperators(): array
    {
        return $this->operators;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return implode('', $this->getOperators());
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}

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
     * @param int $index
     * @return string
     */
    public function getOperatorIndex($index): string
    {
        if ($index < 0) {
            $index = 0;
        }
        if (sizeof($this->operators) <= $index + 1) {
            $index = sizeof($this->operators) - 1;
        }
        if ($index < 0) {
            return '';
        }

        return $this->operators[$index];
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}

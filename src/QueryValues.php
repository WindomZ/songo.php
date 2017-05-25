<?php declare(strict_types=1);

namespace Songo;

class QueryValues
{
    /**
     * @var array
     */
    protected $operators;


    /**
     * @var string
     */
    protected $value;

    public function __construct(QueryValue $value)
    {
    }
}

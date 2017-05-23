<?php declare(strict_types=1);

namespace Songo;

class Query
{
    /**
     * @var array
     */
    protected $includes = array();

    /**
     * @var array
     */
    protected $query = array();

    /**
     * @var array
     */
    protected $keys = array();

    /**
     * @var int
     */
    protected $size = 0;

    protected function __construct()
    {
    }

    public function addQuery(string $key, $value)
    {
    }

    /**
     * Analyze query data.
     */
    public function analyze()
    {
    }
}

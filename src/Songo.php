<?php declare(strict_types=1);

namespace Songo;

class Songo extends Parser
{

    /**
     * @var array
     */
    protected $least = array();

    /**
     * @var array
     */
    protected $must = array();

    public function __construct()
    {
        parent::__construct();
    }
}

<?php declare(strict_types=1);

namespace Songo;

class Parser extends Query
{
    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var int
     */
    protected $page = 0;

    /**
     * @var array
     */
    protected $sort = array();

    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * Reset all params to default.
     */
    public function reset()
    {
        $this->limit = 0;
        $this->page = 0;
        $this->sort = array();
    }

    /**
     * Parse URL.
     *
     * @param  string $url The URL
     * @return array Parse the results
     */
    public function parseRawURL(string $url): array
    {
        $str = parse_url($url, PHP_URL_QUERY);
        if (!$str) {
            return null;
        }
        parse_str($str, $output);
        $output['_query'] = $str;

        $this->reset();

        $strs = explode('&', $str);
        foreach ($strs as $s) {
            $l = strpos($s, '=');
            if (!$l || (strlen($s) <= $l + 1)) {
                continue;
            }
            $key = strtolower(substr($s, 0, $l));
            $value = trim(substr($s, $l + 1));

            switch ($key) {
                case '_limit':
                    if (is_numeric($value)) {
                        $this->limit = intval($value);
                    }
                    break;
                case '_page':
                    if (is_numeric($value)) {
                        $this->page = intval($value);
                    }
                    break;
                case '_sort':
                    array_push($this->sort, $value);
                    break;
                default:
                    $this->setQuery($key, $value);
                    break;
            }
        }

        $output['_limit'] = $this->limit;
        $output['_page'] = $this->page;
        $output['_sort'] = $this->sort;

        $this->analyze();

        return $output;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return array
     */
    public function getSort(): array
    {
        return $this->sort;
    }
}

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
    protected $excludes = array();

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

    /**
     * Reset all params to default.
     */
    public function reset()
    {
        $this->includes = array();
        $this->excludes = array();
        $this->query = array();
        $this->keys = array();
        $this->size = 0;
    }

    public function getQuery(string $key)
    {
        if (array_key_exists($key, $this->query)) {
            return $this->query[$key];
        }

        return '';
    }

    public function setQuery(string $key, string $value)
    {
        if (empty($key) || empty($value)) {
            return;
        } elseif (array_key_exists($key, $this->excludes)) {
            return;
        }

        if (strpos($value, ',$') > 0 || strpos($value, ', $') > 0) {
            $strs = explode(',', $value);
            foreach ($strs as $str) {
                $this->setQuery($key, $str);
            }

            return;
        }

        $l = strrpos($key, '$');
        if ($l && $l >= 0) {
            $value = substr($key, 0, $l).$value;
            $key = substr($key, $l + 1);
        }

        if (empty($key) || empty($value)) {
            return;
        }

        if (array_key_exists($key, $this->query)) {
            array_push($this->query[$key], $value);
        } else {
            $this->query[$key] = array($value);
            array_push($this->keys, $key);
        }
        $this->size++;
    }

    /**
     * Analyze query data.
     */
    public function analyze()
    {
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }
}

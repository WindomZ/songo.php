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
    protected function reset()
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

        return null;
    }

    protected function setQuery(string $key, string $value)
    {
        if (empty($key) || !isset($value)) {
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

        if (empty($key) || !isset($value)) {
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
    public function analyze(): string
    {
        if (!empty($this->includes)) {
            foreach ($this->includes as $k => $i) {
                if (!array_key_exists($k, $this->query)) {
                    return 'songo: missing key: '.$k;
                }
            }
        }

        return '';
    }

    /**
     * @param string $key
     * @return Query
     */
    public function setInclude(string $key): self
    {
        if (empty($key)) {
        } elseif (array_key_exists($key, $this->includes)) {
        } else {
            $this->includes[$key] = true;
        }

        return $this;
    }

    /**
     * @param string $key
     * @return Query
     */
    public function setExclude(string $key): self
    {
        if (empty($key)) {
        } elseif (array_key_exists($key, $this->excludes)) {
        } else {
            $this->excludes[$key] = false;
        }

        return $this;
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

    /**
     * @param $key
     * @return array
     */
    public function getValues($key): array
    {
        $result = array();

        $vs = $this->getQuery($key);
        if (empty($vs)) {
            return null;
        }
        foreach ($vs as $i => $v) {
            $qv = new QueryValue($v);
            if (!empty($qv)) {
                array_push($result, $qv);
            }
        }

        return $result;
    }

    /**
     * @param $key
     * @return array
     */
    public function getQueryValues($key): array
    {
        $result = array();

        $values = $this->getValues($key);
        if (!empty($values)) {
            foreach ($values as $i => $v) {
                array_push($result, $v->getQuery());
            }
        }

        return $result;
    }
}

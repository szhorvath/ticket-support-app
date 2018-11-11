<?php

namespace App;

use ArrayAccess;

class Container implements ArrayAccess
{
    protected $items = [];

    protected $cache = [];

    public function __construct(array $items = [])
    {
        foreach ($items as $key => $item) {
            $this->offsetSet($key, $item);
        }
    }

    public function offsetSet($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function offsetGet($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $item = $this->items[$key]($this);

        $this->cache[$key] = $item;

        return $item;
    }

    public function offsetUnset($key)
    {
        if ($this->has($key)) {
            unset($this->items[$key]);
        }
    }

    public function offsetExists($key)
    {
        return isset($this->items[$key]);
    }

    public function has($key)
    {
        return $this->offsetExists($key);
    }

    public function __get($property)
    {
        return $this->offsetGet($property);
    }
}

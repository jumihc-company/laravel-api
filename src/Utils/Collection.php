<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\HigherOrderCollectionProxy;

class Collection extends BaseCollection
{
    public function __get($key)
    {
        if (! in_array($key, static::$proxies)) {
            return $this->get($key);
        }

        return new HigherOrderCollectionProxy($this, $key);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __unset($name)
    {
        $this->offsetUnset($name);
    }
}

<?php

namespace Dxw\Iguana;

class Helpers
{
    protected $functions;

    public function registerFunction($name, $callable)
    {
        $this->functions[$name] = $callable;
    }

    public function __call($name, $args)
    {
        return call_user_func_array($this->functions[$name], $args);
    }
}

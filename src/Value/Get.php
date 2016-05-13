<?php

namespace Dxw\Iguana\Value;

class Get extends ArrayBase
{
    public function __construct()
    {
        $this->value = $_GET;
    }
}

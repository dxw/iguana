<?php

namespace Dxw\Iguana\Value;

class Cookie extends ArrayBase
{
    public function __construct()
    {
        $this->value = stripslashes_deep($_COOKIE);
    }
}

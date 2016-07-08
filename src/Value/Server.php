<?php

namespace Dxw\Iguana\Value;

class Server extends ArrayBase
{
    public function __construct()
    {
        $this->value = stripslashes_deep($_SERVER);
    }
}

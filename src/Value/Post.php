<?php

namespace Dxw\Iguana\Value;

class Post extends ArrayBase
{
    public function __construct()
    {
        $this->value = $_POST;
    }
}

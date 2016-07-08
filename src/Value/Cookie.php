<?php

namespace Dxw\Iguana\Value;

class Cookie extends ArrayBase
{
    protected function getDefault()
    {
        return stripslashes_deep($_COOKIE);
    }
}

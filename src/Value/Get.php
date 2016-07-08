<?php

namespace Dxw\Iguana\Value;

class Get extends ArrayBase
{
    protected function getDefault()
    {
        return stripslashes_deep($_GET);
    }
}

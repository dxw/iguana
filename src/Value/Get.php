<?php

namespace Dxw\Iguana\Value;

class Get extends ArrayBase
{
    protected function getDefault(): array
    {
        return \stripslashes_deep($_GET);
    }
}

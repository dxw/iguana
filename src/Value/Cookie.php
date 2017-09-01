<?php

namespace Dxw\Iguana\Value;

class Cookie extends ArrayBase
{
    protected function getDefault(): array
    {
        return \stripslashes_deep($_COOKIE);
    }
}

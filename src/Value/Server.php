<?php

namespace Dxw\Iguana\Value;

class Server extends ArrayBase
{
    protected function getDefault()
    {
        return stripslashes_deep($_SERVER);
    }
}

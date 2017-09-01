<?php

namespace Dxw\Iguana\Value;

class Server extends ArrayBase
{
    protected function getDefault(): array
    {
        return \stripslashes_deep($_SERVER);
    }
}

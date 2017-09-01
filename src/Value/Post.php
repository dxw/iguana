<?php

namespace Dxw\Iguana\Value;

class Post extends ArrayBase
{
    protected function getDefault(): array
    {
        return \stripslashes_deep($_POST);
    }
}

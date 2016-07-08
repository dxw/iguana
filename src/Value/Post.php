<?php

namespace Dxw\Iguana\Value;

class Post extends ArrayBase
{
    protected function getDefault()
    {
        return stripslashes_deep($_POST);
    }
}

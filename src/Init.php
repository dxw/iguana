<?php

namespace Dxw\Iguana;

class Init
{
    public static function init(/* string */ $dir, /* string */ $namespace)
    {
        $loader = new \Aura\Autoload\Loader();
        $loader->register();
        $loader->addPrefix($namespace, $dir);

        $registrar = \Dxw\Iguana\Registrar::getSingleton();
        $registrar->di($dir.'/di.php', $namespace);

        return $registrar;
    }
}

<?php

namespace Dxw\Iguana;

class Registrar
{
    protected static $singleton;
    protected $di;

    protected function __construct()
    {
        $this->di = [];

        $this->addInstance('Dxw\\Iguana\\Value\\Post', new \Dxw\Iguana\Value\Post());
        $this->addInstance('Dxw\\Iguana\\Value\\Get', new \Dxw\Iguana\Value\Get());
        $this->addInstance('Dxw\\Iguana\\Helpers', new \Dxw\Iguana\Helpers());
    }

    public function di($path)
    {
        call_user_func(function ($registrar) use ($path) {
            require $path;
        }, $this);
    }

    public function addInstance($class, $instance)
    {
        $this->di[$class] = $instance;
    }

    public function getInstance($class)
    {
        return $this->di[$class];
    }

    public function register()
    {
        foreach ($this->di as $instance) {
            if ($instance instanceof \Dxw\Iguana\Registerable) {
                $instance->register();
            }
        }
    }

    public static function getSingleton()
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new self();
        }

        return self::$singleton;
    }
}

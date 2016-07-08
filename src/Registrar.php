<?php

namespace Dxw\Iguana;

class Registrar
{
    protected static $singleton;
    protected $di;

    protected function __construct()
    {
        $this->di = [];

        $this->setNamespace('Dxw\\Iguana');
        $this->addInstance(new \Dxw\Iguana\Value\Post());
        $this->addInstance(new \Dxw\Iguana\Value\Get());
        $this->addInstance(new \Dxw\Iguana\Value\Server());
        $this->addInstance(new \Dxw\Iguana\Value\Cookie());
    }

    public function di(/* string */ $path, /* string */ $namespace)
    {
        $this->setNamespace($namespace);

        call_user_func(function ($registrar) use ($path) {
            require $path;
        }, $this);
    }

    public function addInstance($class, $instance=null)
    {
        // Shorthand
        // ->addInstance(new \MyClass());
        if ($instance === null && gettype($class) === 'object') {
            $instance = $class;
            $class = get_class($instance);
        }

        $this->di[$this->namespace][$class] = $instance;
    }

    public function getInstance($class)
    {
        if (!isset($this->di[$this->namespace][$class])) {
            if (isset($this->di['Dxw\\Iguana'][$class])) {
                return $this->di['Dxw\\Iguana'][$class];
            } else {
                throw new \Exception('instance undefined');
            }
        }

        return $this->di[$this->namespace][$class];
    }

    public function register()
    {
        foreach ($this->di as $classes) {
            foreach ($classes as $instance) {
                if ($instance instanceof \Dxw\Iguana\Registerable) {
                    $instance->register();
                }
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

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        if (!isset($this->di[$this->namespace])) {
            $this->di[$this->namespace] = [];
        }
    }
}

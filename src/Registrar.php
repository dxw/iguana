<?php

namespace Dxw\Iguana;

class Registrar
{
    /** @var self */
    protected static $singleton;
    /** @var array */
    protected $di;
    /** @var string */
    protected $namespace;

    protected function __construct()
    {
        $this->namespace = '';
        $this->di = [];

        $this->setNamespace('Dxw\\Iguana');
        $this->addInstance(new \Dxw\Iguana\Value\Post());
        $this->addInstance(new \Dxw\Iguana\Value\Get());
        $this->addInstance(new \Dxw\Iguana\Value\Server());
        $this->addInstance(new \Dxw\Iguana\Value\Cookie());
    }

    /** @return void */
    public function di(string $path, string $namespace)
    {
        $this->setNamespace($namespace);

        call_user_func(
            /** @return void */
            function ($registrar) use ($path) {
                require $path;
            },
            $this
        );
    }

    /** @return void */
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

    /** @return mixed */
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

    /** @return void */
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

    public static function getSingleton(): self
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new self();
        }

        return self::$singleton;
    }

    /** @return void */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        if (!isset($this->di[$this->namespace])) {
            $this->di[$this->namespace] = [];
        }
    }
}

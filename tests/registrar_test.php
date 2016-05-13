<?php

class MyRegisterable implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        global $called;
        ++$called;
    }
}

class MyUnregisterable
{
    public function register()
    {
        global $called;
        ++$called;
    }
}

class Registrar_Test extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        $cls = new ReflectionClass(\Dxw\Iguana\Registrar::class);

        $property = $cls->getProperty('singleton');
        $property->setAccessible(true);
        $property->setValue(null);
    }

    public function testSingleton()
    {
        $registrar1 = \Dxw\Iguana\Registrar::getSingleton();
        $registrar2 = \Dxw\Iguana\Registrar::getSingleton();

        $this->assertSame($registrar1, $registrar2);
    }

    //TODO: this causes fatal errors in PHP5 - uncomment when we migrate to PHP7
    // public function testInstantiationErrors()
    // {
    //     $this->setExpectedException(\Error::class);

    //     new \Dxw\Iguana\Registrar();
    // }

    public function testDi()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();

        $file = \org\bovigo\vfs\vfsStream::setup()->url().'/di.php';

        file_put_contents($file, '<?php global $called, $instance; $called++; $instance = $registrar;');

        global $called, $instance;
        $called = 0;
        $instance = null;

        $registrar->di($file);

        $this->assertEquals(1, $called);
        $this->assertSame($registrar, $instance);
    }

    public function testGetInstanceUndefined()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();

        try {
            $registrar->getInstance('meow');
        } catch (\Exception $e) {
            $this->assertEquals('instance undefined', $e->getMessage());

            return;
        }

        $this->fail('Expected \\Exception to be thrown.');
    }

    public function testDefaultInstances()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();

        $this->assertInstanceOf(
            \Dxw\Iguana\Value\Post::class,
            $registrar->getInstance('Dxw\\Iguana\\Value\\Post')
        );
        $this->assertInstanceOf(
            \Dxw\Iguana\Value\Get::class,
            $registrar->getInstance('Dxw\\Iguana\\Value\\Get')
        );
        $this->assertInstanceOf(
            \Dxw\Iguana\Helpers::class,
            $registrar->getInstance('Dxw\\Iguana\\Helpers')
        );
    }

    public function testAddInstance()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();
        $instance = (object) [];

        $registrar->addInstance('123', $instance);

        $this->assertSame($instance, $registrar->getInstance('123'));
    }

    public function testRegisterCallsRegisterable()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();

        $registrar->addInstance('MyRegisterable', new \MyRegisterable());

        global $called;
        $called = 0;

        $registrar->register();

        $this->assertEquals(1, $called);
    }

    public function testRegisterDoesNotCallUnregisterable()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();

        $registrar->addInstance('MyUnregisterable', new \MyUnregisterable());

        global $called;
        $called = 0;

        $registrar->register();

        $this->assertEquals(0, $called);
    }
}

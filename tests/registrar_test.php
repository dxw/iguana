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
    public function setUp()
    {
        \WP_Mock::setUp();

        \WP_Mock::wpFunction('stripslashes_deep', [
            'return' => function ($array) {
                return $array;
            },
        ]);
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();

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

    public function testInstantiationErrors()
    {
        // TODO: this causes fatal errors in PHP5
        // marked as an incomplete test in the meantime
        $this->markTestIncomplete();

        $this->setExpectedException(\Error::class);

        new \Dxw\Iguana\Registrar();
    }

    public function testDi()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();

        $file = \org\bovigo\vfs\vfsStream::setup()->url().'/di.php';

        file_put_contents($file, '<?php global $called, $instance; $called++; $instance = $registrar;');

        global $called, $instance;
        $called = 0;
        $instance = null;

        $registrar->di($file, 'My\\Namespace');

        $this->assertEquals(1, $called);
        $this->assertSame($registrar, $instance);
    }

    public function testDiMixedNamespaces()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();

        $file1 = \org\bovigo\vfs\vfsStream::setup()->url().'/di1.php';
        $file2 = \org\bovigo\vfs\vfsStream::setup()->url().'/di2.php';

        file_put_contents($file1, '<?php $registrar->addInstance("x", 1);');
        file_put_contents($file2, '<?php $registrar->addInstance("x", 2);');

        $registrar->di($file1, 'NS1');
        $registrar->di($file2, 'NS2');

        $this->assertEquals(2, $registrar->getInstance('x'));

        $registrar->setNamespace('NS1');

        $this->assertEquals(1, $registrar->getInstance('x'));
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
            $registrar->getInstance(\Dxw\Iguana\Value\Post::class)
        );
        $this->assertInstanceOf(
            \Dxw\Iguana\Value\Get::class,
            $registrar->getInstance(\Dxw\Iguana\Value\Get::class)
        );
        $this->assertInstanceOf(
            \Dxw\Iguana\Value\Server::class,
            $registrar->getInstance(\Dxw\Iguana\Value\Server::class)
        );
        $this->assertInstanceOf(
            \Dxw\Iguana\Value\Cookie::class,
            $registrar->getInstance(\Dxw\Iguana\Value\Cookie::class)
        );
    }

    public function testDefaultInstancesInDifferentNamespace()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();

        $registrar->setNamespace('meow');

        $this->assertInstanceOf(
            \Dxw\Iguana\Value\Post::class,
            $registrar->getInstance('Dxw\\Iguana\\Value\\Post')
        );
        $this->assertInstanceOf(
            \Dxw\Iguana\Value\Get::class,
            $registrar->getInstance('Dxw\\Iguana\\Value\\Get')
        );
    }

    public function testAddInstance()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();
        $instance = (object) [];

        $registrar->addInstance('123', $instance);

        $this->assertSame($instance, $registrar->getInstance('123'));
    }

    public function testAddInstanceShorthand()
    {
        $registrar = \Dxw\Iguana\Registrar::getSingleton();
        $instance = new \MyUnregisterable();

        $registrar->addInstance($instance);

        $this->assertSame($instance, $registrar->getInstance('MyUnregisterable'));
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

# iguana

An extensible theme and plugin framework for WordPress.

Makes dependency injection easy, and makes it easy to register helper functions for use in your templates.

## Theme templates that use Iguana

- [whippet-theme-template](https://github.com/dxw/whippet-theme-template)

## Installation

Add the library to your theme:

    $ composer require dxw/iguana=dev-master

Then simply add two files to your application:

`app/load.php`:

```
<?php

require __DIR__.'/../vendor.phar';

$loader = new \Aura\Autoload\Loader();
$loader->register();
$loader->addPrefix('Dxw\\MyTheme', __DIR__);

$registrar = \Dxw\Iguana\Registrar::getSingleton();
$registrar->di(__DIR__.'/di.php');

function h()
{
    return \Dxw\Iguana\Registrar::getSingleton()->getInstance('Dxw\\Iguana\\Helpers');
}

return $registrar;
```

(Replace `Dxw\\MyTheme` with the namespace of your own application.)

`app/di.php`:

Can start out blank:

```
<?php
```

But eventually it will contain the code necessary to construct the dependency graph of your code, i.e.:

```
$registrar->addInstance('Dxw\\MyTheme\\Lib\\Whippet\\Layout', new \Dxw\MyTheme\Lib\Whippet\Layout());
$registrar->addInstance('Dxw\\MyTheme\\Lib\\Whippet\\TemplateTags', new \Dxw\MyTheme\Lib\Whippet\TemplateTags(
    $registrar->getInstance('Dxw\\Iguana\\Helpers')
));
```

## What Iguana provides

### For your dependency-injected class structure

Your classes can automatically register themselves:

```
<?php

namespace Dxw\MyTheme;

class MyClass implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter(...);
    }
}
```

Any instance added to the registrar that implements `\Dxw\Iguana\Registerable` will have its `register()` method called.

This means you don't have to remember call the `register()` method somewhere after adding it to `app/di.php`.

And moving the calls to `add_filter()`, 'register_xyz()`, etc out from the constructor makes it much easier to test.

### For your template code

Your classes can easily declare helper functions:

```
<?php

namespace Dxw\MyTheme;

class MyClass
{
    public function __construct(\Dxw\Iguana\Helpers $helpers)
    {
        $helpers->registerFunction('myFunc', [$this, 'myFunc']);
    }

    public function myFunc($a)
    {
        echo esc_html($a + 1);
    }
}
```

And to call this helper function from a template, simply:

```
<?php h()->myFunc(4) ?>
```

Using `h()` means that you only need to pollute the global namespace with one function. And `h()` is a lot shorter than typing out the full namespace.

### For your background processes and cron jobs

If you're running a background process or cron job outside of WordPress, you can simply `$registrar = require 'path/to/load.php';` to load all the code in your theme/plugin. If you need to register it, you can do it piece-by-piece, or you can `$registrar->register();` to register it all at once.

# iguana

An extensible theme and plugin framework for WordPress.

Makes dependency injection easy.

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

return \Dxw\Iguana\Init::init(__DIR__, 'Dxw\\MyTheme');
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
    $registrar->getInstance('Dxw\\MyTheme\\OtherClass')
));
```

## What Iguana provides

### For your dependency-injected class structure

Your classes can automatically register themselves (or rather, indicate that they need to be registered):

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

### For your background processes and cron jobs

If you're running a background process or cron job outside of WordPress, you can simply `$registrar = require 'path/to/load.php';` to load all the code in your theme/plugin. If you need to register it, you can do it piece-by-piece, or you can `$registrar->register();` to register it all at once.

# iguana

An extensible theme and plugin framework for WordPress.

## Components

- **iguana** is the library that allows themes and plugins to set up autoloading and dependency injection without nearly as much boilerplate code
- [iguana-theme](https://github.com/dxw/iguana-theme) builds on iguana and allows themes to register helper functions and use template layouts

## Theme templates that use Iguana

- [whippet-theme-template](https://github.com/dxw/whippet-theme-template)

## Installation

Add the iguana library to your theme or plugin:

```
$ composer require dxw/iguana
```

Add these two lines to `templates/functions.php` (modify appropriately if `functions.php` isn't in a subdirectory):

```
$registrar = require __DIR__.'/../app/load.php';
$registrar->register();
```

Add a new file called `app/load.php`:

```
<?php

require __DIR__.'/../vendor.phar';

return \Dxw\Iguana\Init::init(__DIR__, 'MyTheme');
```

(Replace `MyTheme` with the namespace of your own theme/plugin.)

Add a new file called `app/di.php`:

It can start out blank:

```
<?php
```

But eventually it will contain the code necessary to construct the dependency graph of your code, i.e.:

```
$registrar->addInstance(new \MyTheme\MyClass());
$registrar->addInstance(new \MyTheme\MyOtherClass(
    $registrar->getInstance(\MyTheme\MyClass::class)
));
```

## What Iguana provides

### For your dependency-injected class structure

Your classes can indicate that they have code that needs running (this is run on the call to `$registrar->register()` in `functions.php`):

```
<?php

namespace MyTheme;

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

And moving the calls to `add_filter()`, 'register_xyz()`, etc out of the constructor makes unit testing easier.

### For your background processes and cron jobs

If you're running a background process or cron job outside of WordPress, you can `$registrar = require('path/to/load.php');` to load all the code in your theme/plugin. If you need to register it, you can do it piece-by-piece, or you can `$registrar->register();` to register it all at once.

## Licence

[MIT](COPYING.md)

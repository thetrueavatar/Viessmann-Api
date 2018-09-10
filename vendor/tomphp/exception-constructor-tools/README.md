# Exception Constructor Tools

A simple PHP trait which makes creating static constructors for exceptions nicer.

## Installation

```
$ composer require tomphp/exception-constructor-tools
```

## Usage

Define your exception:

```php
<?php

use TomPHP\ExceptionConstructorTools\ExceptionConstructorTools;

class MyExceptionClass extends \RuntimeException
{
    use ExceptionConstructorTools;

    public static function forEntity($entity)
    {
        return self::create(
            'There was an error with an entity of type %s with value of %s.',
            [
                self::typeToString($entity)
                self::valueToString($entity)
            ]
        );
    }
}
```

Throw your exception:

```php
if ($errorOccurred) {
    throw MyExceptionClass::forEntity($entity);
}
```

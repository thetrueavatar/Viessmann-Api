<?php

namespace tests\support;

use TomPHP\ExceptionConstructorTools;

class ExampleException extends \RuntimeException
{
    use ExceptionConstructorTools;

    public static function fromFormatString($format, $param)
    {
        return self::create($format, [$param]);
    }

    public static function fromCode($code)
    {
        return self::create('', [], $code);
    }

    public static function fromPreviousException($previous)
    {
        return self::create('', [], 0, $previous);
    }

    public static function withTypeInMessage($param)
    {
        return self::create(self::typeToString($param));
    }

    public static function withValueInMessage($value)
    {
        return self::create(self::valueToString($value));
    }

    public static function withListInMessage($param)
    {
        return self::create(self::listToString($param));
    }
}

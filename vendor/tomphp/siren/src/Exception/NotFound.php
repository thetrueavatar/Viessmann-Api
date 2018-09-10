<?php declare(strict_types=1);

namespace TomPHP\Siren\Exception;

use TomPHP\ExceptionConstructorTools;

final class NotFound extends \RuntimeException implements Exception
{
    use ExceptionConstructorTools;

    const PROPERTY = 1;
    const LINK     = 2;
    const ACTION   = 3;

    /**
     * @private
     */
    const PROPERTY_MESSAGE = 'Property "%s" was not found.';

    /**
     * @private
     */
    const LINK_MESSAGE = 'Link "%s" was not found.';

    /**
     * @private
     */
    const ACTION_MESSAGE = 'Action "%s" was not found.';

    public static function forProperty(string $name) : self
    {
        return self::create(self::PROPERTY_MESSAGE, [$name], self::PROPERTY);
    }

    public static function forLink(string $rel) : self
    {
        return self::create(self::LINK_MESSAGE, [$rel], self::LINK);
    }

    public static function forAction(string $name) : self
    {
        return self::create(self::ACTION_MESSAGE, [$name], self::ACTION);
    }
}

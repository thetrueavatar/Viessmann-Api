<?php

namespace TomPHP;

/**
 * This trait provides useful static methods which can be used to help create
 * static constructors for exceptions.
 */
trait ExceptionConstructorTools
{
    /**
     * Create an instance of the exception with a formatted message.
     *
     * @param string     $message  The exception message in sprintf format.
     * @param array      $params   The sprintf parameters for the message.
     * @param int        $code     Numeric exception code.
     * @param \Exception $previous The previous exception.
     *
     * @return static
     */
    protected static function create(
        $message,
        array $params = [],
        $code = 0,
        \Exception $previous = null
    ) {
        return new static(sprintf($message, ...$params), $code, $previous);
    }

    /**
     * Returns a string representation of the type of a variable.
     *
     * @param mixed $variable
     *
     * @return string
     */
    protected static function typeToString($variable)
    {
        return is_object($variable)
            ? get_class($variable)
            : '[' . gettype($variable) . ']';
    }

    /**
     * Returns a string representation of the value.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected static function valueToString($value)
    {
        switch (gettype($value)) {
            case 'string':
                return '"' . addslashes($value) . '"';
            case 'boolean':
                return $value ? 'true' : 'false';
            default:
                return $value;
        }
    }

    /**
     * Returns the list as a formatted string.
     *
     * @param string[] $list
     *
     * @return string
     */
    protected static function listToString(array $list)
    {
        if (empty($list)) {
            return '[]';
        }

        $list = array_map(['static', 'valueToString'], $list);

        return '[' . implode(', ', $list) . ']';
    }
}

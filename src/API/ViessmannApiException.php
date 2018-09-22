<?php
/**
 * Created by PhpStorm.
 * User: clibois
 * Date: 17/09/18
 * Time: 10:46
 */
final class ViessmannApiException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
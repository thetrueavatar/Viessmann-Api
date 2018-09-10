<?php declare(strict_types=1);

namespace TomPHP\Siren;

interface EntityRepresentation
{
    public static function fromArray(array $array) : self;

    public function toArray() : array;
}

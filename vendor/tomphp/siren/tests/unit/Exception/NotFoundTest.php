<?php declare(strict_types=1);

namespace tests\unit\TomPHP\Siren\Exception;

use TomPHP\Siren\Exception\Exception;
use TomPHP\Siren\Exception\NotFound;

final class NotFoundTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_is_a_package_exception()
    {
        assertInstanceOf(Exception::class, new NotFound());
    }

    /** @test */
    public function it_is_a_RuntimeException()
    {
        assertInstanceOf(\RuntimeException::class, new NotFound());
    }

    /** @test */
    public function on_forProperty_it_sets_the_message()
    {
        assertSame(
            'Property "some-property" was not found.',
            NotFound::forProperty('some-property')->getMessage()
        );
    }

    /** @test */
    public function on_forProperty_it_sets_the_code()
    {
        assertSame(
            NotFound::PROPERTY,
            NotFound::forProperty('some-property')->getCode()
        );
    }

    /** @test */
    public function on_forLink_it_sets_the_message()
    {
        assertSame(
            'Link "some-link" was not found.',
            NotFound::forLink('some-link')->getMessage()
        );
    }

    /** @test */
    public function on_forLink_it_sets_the_code()
    {
        assertSame(
            NotFound::LINK,
            NotFound::forLink('some-link')->getCode()
        );
    }
}

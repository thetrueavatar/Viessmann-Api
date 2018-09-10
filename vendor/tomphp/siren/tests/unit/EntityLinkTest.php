<?php declare(strict_types=1);

namespace tests\unit\TomPHP\Siren;

use TomPHP\Siren\EntityLink;
use TomPHP\Siren\EntityRepresentation;

final class EntityLinkTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_is_an_entity_representation()
    {
        $link = new EntityLink(['example-rel'], 'http://api.com/example');

        assertInstanceOf(EntityRepresentation::class, $link);
    }

    /** @test */
    public function on_getArray_it_returns_an_array_for_minimal_details()
    {
        $link = new EntityLink(['example-rel'], 'http://api.com/example');

        assertEquals(
            [
                'rel'  => ['example-rel'],
                'href' => 'http://api.com/example',
            ],
            $link->toArray()
        );
    }

    /** @test */
    public function on_getArray_it_returns_an_array_for_all_details()
    {
        $link = new EntityLink(
            ['example-rel'],
            'http://api.com/example',
            ['example-class'],
            'Example Title',
            'application/json'
        );

        assertEquals(
            [
                'rel'   => ['example-rel'],
                'href'  => 'http://api.com/example',
                'class' => ['example-class'],
                'title' => 'Example Title',
                'type'  => 'application/json',
            ],
            $link->toArray()
        );
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_from_minimum_details()
    {
        $link = new EntityLink(['example-rel'], 'http://api.com/example');

        assertEquals($link, EntityLink::fromArray($link->toArray()));
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_from_all_details()
    {
        $link = new EntityLink(
            ['example-rel'],
            'http://api.com/example',
            ['example-class'],
            'Example Title',
            'application/json'
        );

        assertEquals($link, EntityLink::fromArray($link->toArray()));
    }
}

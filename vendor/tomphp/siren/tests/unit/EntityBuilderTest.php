<?php declare(strict_types=1);

namespace tests\unit\TomPHP\Siren;

use TomPHP\Siren\Entity;
use TomPHP\Siren\Link;

final class EntityBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_addProperies_it_extends_current_properties()
    {
        // Builder test
        $entity = Entity::builder()
            ->addProperties(['a' => 'one', 'b' => 2])
            ->addProperties(['b' => 'two', 'c' => 'three'])
            ->build();

        assertSame(
            ['a' => 'one', 'b' => 'two', 'c' => 'three'],
            $entity->getProperties()
        );
    }

    /** @test */
    public function on_addProperty_it_adds_it_to_the_list()
    {
        $entity = Entity::builder()
            ->addProperty('a', 'one')
            ->addProperty('b', 'two')
            ->build();

        assertSame(['a' => 'one', 'b' => 'two'], $entity->getProperties());
    }

    /** @test */
    public function on_addLink_it_can_accept_a_Link_object()
    {
        $link = new Link(['self'], 'http://api.com');

        $entity = Entity::builder()
            ->addLink($link)
            ->build();

        assertSame([$link], $entity->getLinksByRel('self'));
    }

    /** @test */
    public function on_addLink_it_can_take_scalars()
    {
        $entity = Entity::builder()
            ->addLink('self', 'http://api.com', 'the-class', 'The Link', 'the/type')
            ->build();

        assertEquals(
            [new Link(['self'], 'http://api.com', ['the-class'], 'The Link', 'the/type')],
            $entity->getLinksByRel('self')
        );
    }

    /** @test */
    public function on_addLink_rel_and_class_can_be_arrays()
    {
        $entity = Entity::builder()
            ->addLink(['self'], 'http://api.com', ['the-class'], 'The Link', 'the/type')
            ->build();

        assertEquals(
            [new Link(['self'], 'http://api.com', ['the-class'], 'The Link', 'the/type')],
            $entity->getLinksByRel('self')
        );
    }
}

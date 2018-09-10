<?php declare(strict_types=1);

namespace tests\unit\TomPHP\Siren;

use Psr\Link\LinkProviderInterface;
use TomPHP\Siren\Action;
use TomPHP\Siren\Entity;
use TomPHP\Siren\EntityLink;
use TomPHP\Siren\EntityRepresentation;
use TomPHP\Siren\Exception\NotFound;
use TomPHP\Siren\Link;

final class EntityTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_is_an_entity_representation()
    {
        $entity = Entity::builder()
            ->build();

        assertInstanceOf(EntityRepresentation::class, $entity);
    }

    /** @test */
    public function it_is_a_link_provider()
    {
        $entity = Entity::builder()
            ->build();

        assertInstanceOf(LinkProviderInterface::class, $entity);
    }

    /** @test */
    public function on_getClasses_it_returns_the_classes()
    {
        $entity = Entity::builder()
            ->addClass('class-a')
            ->addClass('class-b')
            ->build();

        assertSame(['class-a', 'class-b'], $entity->getClasses());
    }

    /** @test */
    public function on_getClasses_return_only_one_of_each_class()
    {
        $entity = Entity::builder()
            ->addClass('class-a')
            ->addClass('class-a')
            ->build();

        assertSame(['class-a'], $entity->getClasses());
    }

    /** @test */
    public function on_hasClass_it_returns_false_if_the_class_is_not_present()
    {
        $entity = Entity::builder()
            ->build();

        assertFalse($entity->hasClass('example-class'));
    }

    /** @test */
    public function on_hasClass_it_returns_true_if_the_class_is_present()
    {
        $entity = Entity::builder()
            ->addClass('example-class')
            ->build();

        assertTrue($entity->hasClass('example-class'));
    }

    /** @test */
    public function on_getProperties_it_returns_all_properties()
    {
        $entity = Entity::builder()
            ->addProperties(['a' => 1, 'b' => 2])
            ->build();

        assertSame(['a' => 1, 'b' => 2], $entity->getProperties());
    }

    /** @test */
    public function on_hasProperty_it_returns_false_if_the_property_is_not_present()
    {
        $entity = Entity::builder()
            ->build();

        assertFalse($entity->hasProperty('example-property'));
    }

    /** @test */
    public function on_hasProperty_it_returns_true_if_the_property_is_present()
    {
        $entity = Entity::builder()
            ->addProperty('example-property', 'some value')
            ->build();

        assertTrue($entity->hasProperty('example-property'));
    }

    /** @test */
    public function on_hasProperty_it_returns_true_for_a_null_value_property()
    {
        $entity = Entity::builder()
            ->addProperty('example-property', null)
            ->build();

        assertTrue($entity->hasProperty('example-property'));
    }

    /** @test */
    public function on_getProperty_it_throws_an_exception_if_property_is_not_found()
    {
        $entity = Entity::builder()
            ->build();

        $this->expectException(NotFound::class);

        $entity->getProperty('example-property');
    }

    /** @test */
    public function on_getProperty_it_returns_the_property_value()
    {
        $entity = Entity::builder()
            ->addProperty('example-property', 'property-value')
            ->build();

        assertSame('property-value', $entity->getProperty('example-property'));
    }

    /** @test */
    public function on_getProperty_it_returns_a_null_value_property()
    {
        $entity = Entity::builder()
            ->addProperty('example-property', null)
            ->build();

        assertNull($entity->getProperty('example-property'));
    }

    /** @test */
    public function on_getPropertyOr_it_returns_the_default_if_property_is_not_found()
    {
        $entity = Entity::builder()
            ->build();

        assertSame('default-value', $entity->getPropertyOr('example-property', 'default-value'));
    }

    /** @test */
    public function on_getPropertyOr_it_returns_the_property_value()
    {
        $entity = Entity::builder()
            ->addProperty('example-property', 'property-value')
            ->build();

        assertSame('property-value', $entity->getPropertyOr('example-property', 'default-value'));
    }

    /** @test */
    public function on_hasLink_it_returns_false_if_a_link_with_the_rel_is_not_present()
    {
        $entity = Entity::builder()
            ->addLink('previous', 'http://api.com/previous')
            ->build();

        assertFalse($entity->hasLink('next'));
    }

    /** @test */
    public function on_hasLink_it_returns_false_if_a_link_with_the_rel_is_present()
    {
        $entity = Entity::builder()
            ->addLink('next', 'http://api.com/next')
            ->build();

        assertTrue($entity->hasLink('next'));
    }

    /** @test */
    public function on_getLinks_it_returns_all_added_links()
    {
        $entity = Entity::builder()
            ->addLink('next', 'http://api.com/next')
            ->addLink('previous', 'http://api.com/previous')
            ->build();

        assertEquals(
            [
                new Link(['next'], 'http://api.com/next'),
                new Link(['previous'], 'http://api.com/previous'),
            ],
            $entity->getLinks()
        );
    }

    /** @test */
    public function on_getLinksByRel_it_returns_the_link_by_rel()
    {
        $entity = Entity::builder()
            ->addLink('next', 'http://api.com/next1')
            ->addLink('next', 'http://api.com/next2')
            ->build();

        assertEquals(
            [
                new Link(['next'], 'http://api.com/next1'),
                new Link(['next'], 'http://api.com/next2'),
            ],
            $entity->getLinksByRel('next')
        );
    }

    /** @test */
    public function on_getLinksByRel_it_returns_an_emoty_array_if_no_links_are_found()
    {
        $entity = Entity::builder()
            ->addLink('next', 'http://api.com/next')
            ->build();

        assertSame([], $entity->getLinksByRel('previous'));
    }

    /** @test */
    public function on_getLinksByClass_it_returns_the_link_by_class()
    {
        $entity = Entity::builder()
            ->addLink('next', 'http://api.com/next', 'class-a')
            ->addLink('previous', 'http://api.com/previous', 'class-b')
            ->build();

        assertEquals(
            [
                new Link(['previous'], 'http://api.com/previous', ['class-b']),
            ],
            $entity->getLinksByClass('class-b')
        );
    }

    /** @test */
    public function on_getLinksByClass_it_returns_an_emoty_array_if_no_links_are_found()
    {
        $entity = Entity::builder()
            ->addLink('next', 'http://api.com/next', ['example-class'])
            ->build();

        assertSame([], $entity->getLinksByClass('unknown-class'));
    }

    /** @test */
    public function on_getTitle_it_returns_the_title()
    {
        $entity = Entity::builder()
            ->setTitle('The Title')
            ->build();

        assertSame('The Title', $entity->getTitle());
    }

    /** @test */
    public function on_getActions_it_returns_the_actions()
    {
        $action = Action::builder()
            ->setName('example-action')
            ->setHref('http://api.com/example')
            ->build();

        $entity = Entity::builder()
            ->addAction($action)
            ->build();

        assertEquals([$action], $entity->getActions());
    }

    /** @test */
    public function on_hasAction_it_returns_false_if_there_is_not_a_matching_action()
    {
        $action = Action::builder()
            ->setName('example-action')
            ->setHref('http://api.com/example')
            ->build();

        $entity = Entity::builder()
            ->addAction($action)
            ->build();

        assertFalse($entity->hasAction('unknown-action'));
    }

    /** @test */
    public function on_hasAction_it_returns_true_if_there_is_a_matching_action()
    {
        $action = Action::builder()
            ->setName('example-action')
            ->setHref('http://api.com/example')
            ->build();

        $entity = Entity::builder()
            ->addAction($action)
            ->build();

        assertTrue($entity->hasAction('example-action'));
    }

    /** @test */
    public function on_getAction_it_returns_the_action_if_it_is_found()
    {
        $action = Action::builder()
            ->setName('example-action')
            ->setHref('http://api.com/example')
            ->build();

        $entity = Entity::builder()
            ->addAction($action)
            ->build();

        assertSame($action, $entity->getAction('example-action'));
    }

    /** @test */
    public function on_getAction_it_throws_NotFound_if_there_is_not_matching_action()
    {
        $entity = Entity::builder()
            ->build();

        $this->setExpectedException(
            NotFound::class,
            'Action "add-customer" was not found.',
            NotFound::ACTION
        );

        $entity->getAction('add-customer');
    }

    /** @test */
    public function on_getEntities_it_returns_all_entities()
    {
        $subEntity = new EntityLink(
            ['example-rel'],
            'http://api.com',
            ['example-class'],
            'Example Title',
            'application/json'
        );

        $entity = Entity::builder()
            ->addSubEntity($subEntity)
            ->build();

        assertEquals([$subEntity], $entity->getEntities());
    }

    /** @test */
    public function on_getEntities_it_can_return_real_entities()
    {
        $subEntity = Entity::builder()->build();

        $entity = Entity::builder()
            ->addSubEntity($subEntity)
            ->build();

        assertEquals([$subEntity], $entity->getEntities());
    }

    /** @test */
    public function on_getEntitiesByProperty()
    {
        $tom = Entity::builder()
            ->addProperty('name', 'Tom')
            ->build();

        $jerry = Entity::builder()
            ->addProperty('name', 'jerry')
            ->build();

        $entity = Entity::builder()
            ->addSubEntity($tom)
            ->addSubEntity($jerry)
            ->build();

        assertEquals([$tom], $entity->getEntitiesByProperty('name', 'Tom'));
    }

    /** @test */
    public function on_toArray_it_converts_to_an_array_for_minimal_values()
    {
        $entity = Entity::builder()
            ->build();

        assertSame([], $entity->toArray());
    }

    /** @test */
    public function on_toArray_it_converts_to_an_array()
    {
        $entity = Entity::builder()
            ->addClass('example-class')
            ->addProperties(['a' => 1, 'b' => 2])
            ->setTitle('Example Title')
            ->build();

        assertEquals(
            [
                'class'      => ['example-class'],
                'properties' => ['a' => 1, 'b' => 2],
                'title'      => 'Example Title',
            ],
            $entity->toArray()
        );
    }

    /** @test */
    public function on_toArray_it_includes_link_arrays()
    {
        $link = new Link(['self'], 'http://api.com');

        $entity = Entity::builder()
            ->addLink($link)
            ->build();

        assertEquals(['links' => [$link->toArray()]], $entity->toArray());
    }

    /** @test */
    public function on_toArray_it_includes_action_arrays()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/cusotmer')
            ->setMethod('POST')
            ->build();

        $entity = Entity::builder()
            ->addAction($action)
            ->build();

        assertEquals(['actions' => [$action->toArray()]], $entity->toArray());
    }

    /** @test */
    public function on_toArray_it_includes_entity_arrays()
    {
        $entityLink = new EntityLink(['example-rel'], 'http://api.com/example');

        $entity = Entity::builder()
            ->addSubEntity($entityLink)
            ->build();

        assertEquals(['entities' => [$entityLink->toArray()]], $entity->toArray());
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_with_minimum_details()
    {
        $entity = Entity::builder()
            ->build();

        assertEquals($entity, Entity::fromArray($entity->toArray()));
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_with_all_details()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/cusotmer')
            ->setMethod('POST')
            ->build();

        $entityLink = new EntityLink(['example-rel'], 'http://api.com/example');
        $subEntity  = Entity::builder()->build();

        $entity = Entity::builder()
            ->addClass('example-class')
            ->addProperties(['a' => 1, 'b' => 2])
            ->setTitle('Example Title')
            ->addAction($action)
            ->addLink('self', 'http://api.com')
            ->addSubEntity($entityLink)
            ->addSubEntity($subEntity)
            ->build();

        assertEquals($entity, Entity::fromArray($entity->toArray()));
    }

    /** @test */
    public function on_toJson_it_returns_a_json_string()
    {
        $entity = Entity::builder()
            ->addClass('example-class')
            ->addProperties([
                'a' => 1,
                'b' => 2,
            ])
            ->build();

        assertSame(json_encode($entity->toArray()), $entity->toJson());
    }
}

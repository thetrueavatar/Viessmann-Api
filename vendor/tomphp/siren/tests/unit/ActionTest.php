<?php declare(strict_types=1);

namespace tests\unit\TomPHP\Siren;

use TomPHP\Siren\Action;
use TomPHP\Siren\Field;

final class ActionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_getName_it_returns_the_name()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->build();

        assertSame('add-customer', $action->getName());
    }

    /** @test */
    public function on_getHref_it_returns_the_href()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->build();

        assertSame('http://api.com/customer', $action->getHref());
    }

    /** @test */
    public function on_getClass_it_returns_all_the_classes()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addClass('customer')
            ->addClass('item')
            ->build();

        assertSame(['customer', 'item'], $action->getClasses());
    }

    /** @test */
    public function on_hasClass_it_returns_false_if_class_is_not_present()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addClass('customer')
            ->build();

        assertFalse($action->hasClass('unknown'));
    }

    /** @test */
    public function on_hasClass_it_returns_true_if_class_is_present()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addClass('customer')
            ->build();

        assertTrue($action->hasClass('customer'));
    }

    /** @test */
    public function on_getMethod_it_returns_GET_if_not_set_otherwise()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->build();

        assertSame('GET', $action->getMethod());
    }

    /** @test */
    public function on_getMethod_it_returns_the_method()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->setMethod('PATCH')
            ->build();

        assertSame('PATCH', $action->getMethod());
    }

    /** @test */
    public function on_getTitle_it_returns_the_title()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->setTitle('Example Title')
            ->build();

        assertSame('Example Title', $action->getTitle());
    }

    /** @test */
    public function on_getFields_it_returns_the_fields()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addField(new Field('first-name'))
            ->addField(new Field('last-name'))
            ->build();

        assertEquals(
            [new Field('first-name'), new Field('last-name')],
            $action->getFields()
        );
    }

    /** @test */
    public function on_toArray_it_returns_an_array_for_minimal_details()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->build();

        assertEquals(
            [
                'name'   => 'add-customer',
                'href'   => 'http://api.com/customer',
                'method' => 'GET',
            ],
            $action->toArray()
        );
    }

    /** @test */
    public function on_toArray_it_returns_an_array_for_all_details()
    {
        $field = new Field('first-name');

        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addClass('customer')
            ->setMethod('POST')
            ->setTitle('Add Customer')
            ->addField($field)
            ->build();

        assertEquals(
            [
                'name'   => 'add-customer',
                'href'   => 'http://api.com/customer',
                'method' => 'POST',
                'class'  => ['customer'],
                'title'  => 'Add Customer',
                'fields' => [$field->toArray()],
            ],
            $action->toArray()
        );
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_from_minimum_details()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->build();

        assertEquals($action, Action::fromArray($action->toArray()));
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_from_all_details()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addClass('customer')
            ->setMethod('POST')
            ->setTitle('Add Customer')
            ->addField(new Field('first-name'))
            ->build();

        assertEquals($action, Action::fromArray($action->toArray()));
    }
}

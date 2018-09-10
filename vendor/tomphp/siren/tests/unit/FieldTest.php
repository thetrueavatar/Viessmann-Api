<?php declare(strict_types=1);

namespace tests\unit\TomPHP\Siren;

use TomPHP\Siren\Field;

final class FieldTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_getName_it_returns_the_name()
    {
        $field = new Field('first-name');

        assertSame('first-name', $field->getName());
    }

    /** @test */
    public function on_getClass_it_defaults_to_an_empty_array()
    {
        $field = new Field('last-name');

        assertSame([], $field->getClasses());
    }

    /** @test */
    public function on_getClass_it_returns_the_classes()
    {
        $field = new Field('date-of-birth', ['customer']);

        assertSame(['customer'], $field->getClasses());
    }

    /** @test */
    public function on_getType_it_defaults_to_null()
    {
        $field = new Field('address1');

        assertNull($field->getType());
    }

    /** @test */
    public function on_getType_it_returns_the_type()
    {
        $field = new Field('address2', [], 'text');

        assertSame('text', $field->getType());
    }

    /** @test */
    public function on_getValue_it_defaults_to_null()
    {
        $field = new Field('address1');

        assertNull($field->getValue());
    }

    /** @test */
    public function on_getValue_it_returns_the_type()
    {
        $field = new Field('address2', [], null, 'Address Two');

        assertSame('Address Two', $field->getValue());
    }

    /** @test */
    public function on_getTitle_it_defaults_to_null()
    {
        $field = new Field('city');

        assertNull($field->getTitle());
    }

    /** @test */
    public function on_getTitle_it_returns_the_type()
    {
        $field = new Field('postcode', [], null, null, 'Post Code');

        assertSame('Post Code', $field->getTitle());
    }

    /** @test */
    public function on_toArray_it_returns_an_array_for_minimal_details()
    {
        $field = new Field('country');

        assertSame(['name' => 'country'], $field->toArray());
    }

    /** @test */
    public function on_toArray_it_returns_for_all_details()
    {
        $field = new Field(
            'email-address',
            ['email-class'],
            'email',
            'someone@example.com',
            'Email'
        );

        assertEquals(
            [
                'name'  => 'email-address',
                'class' => ['email-class'],
                'type'  => 'email',
                'value' => 'someone@example.com',
                'title' => 'Email',
            ],
            $field->toArray()
        );
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_from_minimum_details()
    {
        $field = new Field('country');

        assertEquals($field, Field::fromArray($field->toArray()));
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_from_all_details()
    {
        $field = new Field(
            'email-address',
            ['email-class'],
            'email',
            'someone@example.com',
            'Email'
        );

        assertEquals($field, Field::fromArray($field->toArray()));
    }
}

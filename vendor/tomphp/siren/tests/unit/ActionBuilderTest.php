<?php declare(strict_types=1);

namespace tests\unit\TomPHP\Siren;

use TomPHP\Siren\Action;
use TomPHP\Siren\Field;

final class ActionBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_addField_it_accepts_an_Field_object()
    {
        $field = new Field('email-address');

        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addField($field)
            ->build();

        assertSame([$field], $action->getFields());
    }

    /** @test */
    public function on_addField_it_accepts_scalars()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addField('email-address', 'email-class', 'email', 'someone@example.com', 'Email Addres')
            ->build();

        assertEquals(
            [new Field('email-address', ['email-class'], 'email', 'someone@example.com', 'Email Addres')],
            $action->getFields()
        );
    }

    /** @test */
    public function on_addField_it_accepts_classes_as_an_array()
    {
        $action = Action::builder()
            ->setName('add-customer')
            ->setHref('http://api.com/customer')
            ->addField('email-address', ['email-class'], 'email', 'someone@example.com', 'Email Addres')
            ->build();

        assertEquals(
            [new Field('email-address', ['email-class'], 'email', 'someone@example.com', 'Email Addres')],
            $action->getFields()
        );
    }
}

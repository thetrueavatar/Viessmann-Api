<?php declare(strict_types=1);

namespace tests\unit\TomPHP\Siren;

use Psr\Link\LinkInterface;
use TomPHP\Siren\Link;

final class LinkTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_is_a_PSR13_link()
    {
        assertInstanceOf(LinkInterface::class, new Link(['rel'], 'http://api.com'));
    }

    /** @test */
    public function it_must_have_at_least_one_rel()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Link([], 'http://api.com');
    }

    /** @test */
    public function on_hasRel_it_returns_true_if_the_rel_is_pesent()
    {
        $link = new Link(['self'], 'http://api.com');

        assertTrue($link->hasRel('self'));
    }

    /** @test */
    public function on_hasRel_it_returns_false_if_the_rel_is_not_pesent()
    {
        $link = new Link(['next'], 'http://api.com/next');

        assertFalse($link->hasRel('self'));
    }

    /** @test */
    public function on_getHref_it_returns_the_href()
    {
        $link = new Link(['next'], 'http://api.com/next');

        assertSame('http://api.com/next', $link->getHref());
    }

    /** @test */
    public function on_getClasses_it_returns_the_classes()
    {
        $link = new Link(['next'], 'http://api.com/next', ['class-one', 'class-two']);

        assertSame(['class-one', 'class-two'], $link->getClasses());
    }

    /** @test */
    public function on_hasClass_it_returns_true_if_class_rel_is_pesent()
    {
        $link = new Link(['self'], 'http://api.com', ['example-class']);

        assertTrue($link->hasClass('example-class'));
    }

    /** @test */
    public function on_hasClass_it_returns_false_if_the_class_is_not_pesent()
    {
        $link = new Link(['next'], 'http://api.com/next', ['example-class']);

        assertFalse($link->hasClass('unknown-class'));
    }

    /** @test */
    public function on_getTitle_it_returns_the_title()
    {
        $link = new Link(['next'], 'http://api.com/next', [], 'Next Page');

        assertSame('Next Page', $link->getTitle());
    }

    /** @test */
    public function on_getType_it_returns_the_type()
    {
        $link = new Link(['next'], 'http://api.com/next', [], null, 'application/json');

        assertSame('application/json', $link->getType());
    }

    /** @test */
    public function on_isTemplated_it_returns_false()
    {
        $link = new Link(['next'], 'http://api.com/next');

        assertFalse($link->isTemplated());
    }

    /** @test */
    public function on_getAttributes_it_returns_an_empty_array()
    {
        $link = new Link(['next'], 'http://api.com/next');

        assertSame([], $link->getAttributes());
    }

    /** @test */
    public function on_toArray_it_returns_an_array_with_minium_values()
    {
        $link = new Link(['self'], 'http://api.com/self');

        assertSame(
            [
                'rel'  => ['self'],
                'href' => 'http://api.com/self',
            ],
            $link->toArray()
        );
    }

    /** @test */
    public function on_toArray_it_returns_an_array_will_all_values()
    {
        $link = new Link(
            ['self'],
            'http://api.com/self',
            ['product'],
            'Product',
            'application/json'
        );

        assertSame(
            [
               'rel'   => ['self'],
               'href'  => 'http://api.com/self',
               'class' => ['product'],
               'title' => 'Product',
               'type'  => 'application/json',
            ],
            $link->toArray()
        );
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_from_minimum_details()
    {
        $link = new Link(['self'], 'http://api.com');

        assertEquals($link, Link::fromArray($link->toArray()));
    }

    /** @test */
    public function on_fromArray_it_creates_an_instance_from_all_details()
    {
        $link = new Link(
            ['self'],
            'http://api.com/self',
            ['product'],
            'Product',
            'application/json'
        );

        assertEquals($link, Link::fromArray($link->toArray()));
    }
}

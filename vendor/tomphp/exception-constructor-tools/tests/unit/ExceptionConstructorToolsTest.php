<?php

namespace tests\unit\TomPHP;

use PHPUnit_Framework_TestCase;
use tests\support\ExampleException;
use tests\support\ExampleExtendedException;

final class ExceptionConstructorToolsTest extends PHPUnit_Framework_TestCase
{
    public function testItFormatsTheMessage()
    {
        $exception = ExampleException::fromFormatString('example %s', 'message');

        $this->assertSame('example message', $exception->getMessage());
    }

    public function testItCanAddAnExceptionCode()
    {
        $exception = ExampleException::fromCode(909);

        $this->assertSame(909, $exception->getCode());
    }

    public function testItCanAddAPreviousException()
    {
        $previous = new \RuntimeException();

        $exception = ExampleException::fromPreviousException($previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testItUsesLateStaticBindings()
    {
        $exception = ExampleExtendedException::fromFormatString('', []);

        $this->assertInstanceOf('tests\support\ExampleExtendedException', $exception);
    }

    public function testItConvertsABuiltInTypeToAMessage()
    {
        $exception = ExampleException::withTypeInMessage(99);

        $this->assertSame('[integer]', $exception->getMessage());
    }

    public function testItConvertsAnObjectToAClassNameMessage()
    {
        $exception = ExampleException::withTypeInMessage(new \stdClass());

        $this->assertSame('stdClass', $exception->getMessage());
    }

    public function testItConvertsAStringValueToAMessage()
    {
        $exception = ExampleException::withValueInMessage('value');

        $this->assertSame('"value"', $exception->getMessage());
    }

    public function testItConvertsAStringWithQuotesValueToAMessage()
    {
        $exception = ExampleException::withValueInMessage('"value"');

        $this->assertSame('"\"value\""', $exception->getMessage());
    }

    public function testItConvertsATrueValueToAMessage()
    {
        $exception = ExampleException::withValueInMessage(true);

        $this->assertSame('true', $exception->getMessage());
    }

    public function testItConvertsAFalseValueToAMessage()
    {
        $exception = ExampleException::withValueInMessage(false);

        $this->assertSame('false', $exception->getMessage());
    }

    public function testItConvertsAnIntValueToAMessage()
    {
        $exception = ExampleException::withValueInMessage(12);

        $this->assertSame('12', $exception->getMessage());
    }

    public function testItConvertsAListOfStringsToAMessage()
    {
        $exception = ExampleException::withListInMessage(['a', 'b', 'c']);

        $this->assertSame('["a", "b", "c"]', $exception->getMessage());
    }

    public function testItConvertsAnEmptyListToAMessage()
    {
        $exception = ExampleException::withListInMessage([]);

        $this->assertSame('[]', $exception->getMessage());
    }

    public function testItConvertsAListOfIntsToAMessage()
    {
        $exception = ExampleException::withListInMessage([1, 2, 3]);

        $this->assertSame('[1, 2, 3]', $exception->getMessage());
    }
}

<?php declare(strict_types=1);

namespace TomPHP\Siren;

use Assert\Assertion;

final class Action
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $href;

    /**
     * @var string[]
     */
    private $classes;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Field[]
     */
    private $fields;

    public static function builder() : ActionBuilder
    {
        return new ActionBuilder();
    }

    public static function fromArray(array $array) : self
    {
        $fields = [];
        if (isset($array['fields'])) {
            $fields = array_map([Field::class, 'fromArray'], $array['fields']);
        }

        return new self(
            $array['name'],
            $array['href'],
            $array['class'] ?? [],
            $array['method'] ?? 'GET',
            $array['title'] ?? null,
            $fields
        );
    }

    /**
     * @param string   $name
     * @param string   $href
     * @param string[] $classes
     * @param string   $method
     * @param Field[]  $fields
     *
     * @internal
     */
    public function __construct(
        string $name,
        string $href,
        array $classes,
        string $method,
        string $title = null,
        array $fields = []
    ) {
        Assertion::allString($classes);
        Assertion::allIsInstanceOf($fields, Field::class);

        $this->name    = $name;
        $this->href    = $href;
        $this->classes = $classes;
        $this->method  = $method;
        $this->title   = $title;
        $this->fields  = $fields;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getHref() : string
    {
        return $this->href;
    }

    /**
     * @return string[]
     */
    public function getClasses() : array
    {
        return $this->classes;
    }

    public function hasClass(string $name) : bool
    {
        return in_array($name, $this->classes, true);
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function toArray() : array
    {
        $result = [
            'name'   => $this->name,
            'href'   => $this->href,
            'method' => $this->method,
        ];

        if (count($this->classes)) {
            $result['class'] = $this->classes;
        }

        if (!is_null($this->title)) {
            $result['title'] = $this->title;
        }

        if (count($this->fields)) {
            $result['fields'] = array_map(
                function (Field $field) {
                    return $field->toArray();
                },
                $this->fields
            );
        }

        return $result;
    }
}

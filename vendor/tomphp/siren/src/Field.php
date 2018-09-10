<?php declare(strict_types=1);

namespace TomPHP\Siren;

final class Field
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $classes;

    /**
     * @var string
     *
     * hidden, text, search, tel, url, email, password, datetime, date, month,
     * week, time, datetime-local, number, range, color, checkbox, radio, file
     */
    private $type;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $title;

    public static function fromArray(array $array) : self
    {
        return new self(
            $array['name'],
            $array['class'] ?? [],
            $array['type'] ?? null,
            $array['value'] ?? null,
            $array['title'] ?? null
        );
    }

    /**
     * @param string   $name
     * @param string[] $classes
     * @param string   $type
     * @param mixed    $value
     */
    public function __construct(
        string $name,
        array $classes = [],
        string $type = null,
        $value = null,
        string $title = null
    ) {
        // assert types

        $this->name    = $name;
        $this->classes = $classes;
        $this->type    = $type;
        $this->value   = $value;
        $this->title   = $title;
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getClasses() : array
    {
        return $this->classes;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function toArray() : array
    {
        $result = [
            'name' => $this->name,
        ];

        if (count($this->classes)) {
            $result['class'] = $this->classes;
        }

        if (!is_null($this->type)) {
            $result['type'] = $this->type;
        }

        if (!is_null($this->value)) {
            $result['value'] = $this->value;
        }

        if (!is_null($this->title)) {
            $result['title'] = $this->title;
        }

        return $result;
    }
}

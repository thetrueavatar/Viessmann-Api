<?php declare(strict_types=1);

namespace TomPHP\Siren;

final class ActionBuilder
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
    private $classes = [];

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var string
     */
    private $title;

    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @return $this
     */
    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function setHref(string $href) : self
    {
        $this->href = $href;

        return $this;
    }

    /**
     * @return $this
     */
    public function addClass(string $name) : self
    {
        $this->classes[] = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function setMethod(string $method) : self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return $this
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param Field|string  $fieldOrName
     * @param string[]|null $classes
     * @param string|null   $type
     * @param mixed         $value
     * @param string|null   $title
     *
     * @return $this
     */
    public function addField(
        $fieldOrName,
        $classes = [],
        string $type = null,
        $value = null,
        string $title = null
    ) : self {
        if (!$fieldOrName instanceof Field) {
            $fieldOrName = new Field(
                $fieldOrName,
                is_array($classes) ? $classes : [$classes],
                $type,
                $value,
                $title
            );
        }

        $this->fields[] = $fieldOrName;

        return $this;
    }

    /**
     * @return Action
     */
    public function build() : Action
    {
        return new Action(
            $this->name,
            $this->href,
            $this->classes,
            $this->method,
            $this->title,
            $this->fields
        );
    }
}

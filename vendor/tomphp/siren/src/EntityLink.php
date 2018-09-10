<?php declare(strict_types=1);

namespace TomPHP\Siren;

use Assert\Assertion;

final class EntityLink implements EntityRepresentation
{
    /**
     * @var array
     */
    private $rel;

    /**
     * @var string
     */
    private $href;

    /**
     * @var array
     */
    private $class;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $type;

    /**
     * @return self
     */
    public static function fromArray(array $array) : EntityRepresentation
    {
        return new self(
            $array['rel'],
            $array['href'],
            $array['class'] ?? [],
            $array['title'] ?? null,
            $array['type'] ?? null
        );
    }

    /**
     * @param string[] $rel
     * @param string   $href
     * @param string[] $class
     * @param string   $title
     * @param string   $type
     */
    public function __construct(array $rel, string $href, array $class = [], string $title = null, string $type = null)
    {
        Assertion::allString($rel);
        Assertion::allString($class);

        $this->rel   = $rel;
        $this->href  = $href;
        $this->class = $class;
        $this->title = $title;
        $this->type  = $type;
    }

    public function toArray() : array
    {
        $result = [
            'rel'  => $this->rel,
            'href' => $this->href,
        ];

        if (count($this->class)) {
            $result['class'] = $this->class;
        }

        if (!is_null($this->title)) {
            $result['title'] = $this->title;
        }

        if (!is_null($this->type)) {
            $result['type'] = $this->type;
        }

        return $result;
    }
}

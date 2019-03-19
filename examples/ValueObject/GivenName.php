<?php
declare(strict_types=1);

namespace ProophExample\ValueObject;

final class GivenName
{
    private $name;

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->name === $other->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

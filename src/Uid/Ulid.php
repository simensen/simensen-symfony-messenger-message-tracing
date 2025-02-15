<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Uid;

use Symfony\Component\Uid\Ulid as SymfonyUlid;

final readonly class Ulid implements \Stringable
{
    private function __construct(private string $value)
    {
    }

    public function fromString(string $value): self
    {
        return new self($value);
    }

    public static function generate(?\DateTimeInterface $time = null): self
    {
        return new self(SymfonyUlid::generate($time));
    }

    public function __toString()
    {
        return $this->value;
    }
}

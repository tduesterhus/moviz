<?php
declare(strict_types=1);

namespace App\Objects;

class ExtMovieId
{
    public function __construct(public readonly string $value)
    {
    }

    public function toString(): string
    {
        return $this->value;
    }

}
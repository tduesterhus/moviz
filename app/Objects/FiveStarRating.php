<?php
declare(strict_types=1);

namespace App\Objects;

use App\Exceptions\InvalidRatingException;

class FiveStarRating
{
    public function __construct(public readonly int $value)
    {
        if ($this->value < 0 || $this->value > 5) {
            throw new InvalidRatingException();
        }
    }
}
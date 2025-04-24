<?php
declare(strict_types=1);

namespace App\Objects;

class MovieRatedShort
{
    public function __construct(
        public readonly ?string $avgRating,
        public readonly MovieShort $movieShort,
    ) { }
}
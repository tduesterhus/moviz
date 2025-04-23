<?php
declare(strict_types=1);

namespace App\Objects;

class MovieListItem
{
    public function __construct(
        public readonly ?string $avgRating,
        public readonly MovieShort $movieShort,
    ) { }
}
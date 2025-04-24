<?php
declare(strict_types=1);

namespace App\Objects;

use Carbon\CarbonImmutable;

class MovieDetails
{
    public function __construct(
        public readonly MovieSourceType $source,
        public readonly ExtMovieId $movieId,
        public readonly string $title,
        public readonly MovieType $type,
        public readonly int $year,
        public readonly ?CarbonImmutable $releaseDate,
        public readonly string $runtime,
        public readonly string $genre,
        public readonly string $director,
        public readonly string $writer,
        public readonly string $actors,
        public readonly string $plot,
        public readonly string $country,
        public readonly string $language,
        public readonly string $awards,
        public readonly string $imageUrl
    )
    {
    }
}
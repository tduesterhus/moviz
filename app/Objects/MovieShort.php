<?php

declare(strict_types=1);

namespace App\Objects;

use App\Models\MovieSource;

class MovieShort
{

    public function __construct(
        public readonly ExtMovieId $extId,
        public readonly string $title,
        public readonly MovieType $type,
        public readonly string $year,
        public readonly string $imageUrl
    ) {
    }

    public static function fromSource(MovieSource $source): MovieShort
    {
        return new self(
            extId: new ExtMovieId($source->source_id),
            title: $source->title,
            type: $source->type,
            year: $source->year,
            imageUrl: $source->image_url,
        );
    }
}
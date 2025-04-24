<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Movie;
use App\Objects\MovieDetails;
use Livewire\Wireable;

class MovieDetailsDTO implements Wireable
{
    public function __construct(
        public readonly ?string $movieUuid,
        public readonly null|string|int $movieId,
        public readonly ?float $avgRating,
        public readonly string $extId,
        public readonly string $source,
        public readonly string $title,
        public readonly string $type,
        public readonly int $year,
        public readonly string $releaseDate,
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
    ) {
    }

    public static function fromExtDetails(?Movie $movie, MovieDetails $details): self
    {
        return new self(
            movieUuid: $movie?->uuid,
            movieId: $movie?->id,
            avgRating: (float)$movie?->avg_rating,
            extId: $details->movieId->toString(),
            source: $details->source->value,
            title: $details->title,
            type: $details->type->translate(),
            year: $details->year,
            releaseDate: $details->releaseDate?->toDateString() ?? 'N/A',
            runtime: $details->runtime,
            genre: $details->genre,
            director: $details->director,
            writer: $details->writer,
            actors: $details->actors,
            plot: $details->plot,
            country: $details->country,
            language: $details->language,
            awards: $details->awards,
            imageUrl: $details->imageUrl
        );
    }

    public function toLivewire(): array
    {
        return get_object_vars($this);
    }

    public static function fromLivewire($value): static
    {
        return new self(...$value);
    }
}
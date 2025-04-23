<?php

declare(strict_types=1);

namespace App\Services;

use App\APIs\OMDbAPI;
use App\Models\Movie;
use App\Objects\ExtMovieId;
use App\Objects\FiveStarRating;
use App\Objects\MovieSourceType;

class MovieRatingService
{

    public function __construct(private readonly OMDbAPI $omdbQuery)
    {
    }

    public function rate(ExtMovieId $extMovieId, int|string $userId, FiveStarRating $rating)
    {
        // try to find the movie by its source id
        $movie = Movie::query()
                      ->whereHas('sources', fn($query) => $query->where('source_id', $extMovieId->toString()))
                      ->first();

        // if the movie does not exist, we create a new movie entry ...
        if ($movie === null) {
            $movie = Movie::query()->create([
                'avg_rating' => 0,
            ]);
        }

        // ... and update the data (implicit update)
        $movieDetails = $this->omdbQuery->movieById($extMovieId->toString());
        $movie->sources()->updateOrCreate(
            ['source_id' => $movieDetails->movieId->toString()],
            [
                'source'    => MovieSourceType::OMDb->value,
                'title'     => $movieDetails->title,
                'year'      => $movieDetails->year,
                'image_url' => $movieDetails->imageUrl,
                'type'      => $movieDetails->type,
            ],
        );

        // finally: create (or update) the rating
        $movie->ratings()->updateOrCreate(
            ['user_id' => $userId, 'movie_id' => $movie->id],
            ['rating' => $rating->value],
        );

        // update the average rating
        $movie->update([
            'avg_rating' => $movie->ratings()->avg('rating') ?? 0
        ]);
    }

}
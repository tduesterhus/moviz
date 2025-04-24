<?php

declare(strict_types=1);

namespace App\Queries;

use App\APIs\OMDbAPI;
use App\DTOs\MovieDetailsDTO;
use App\Models\Movie;
use App\Models\MovieRating;
use App\Objects\MovieRatedShort;
use App\Objects\MovieShort;
use App\Objects\MovieSourceType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MovieQuery
{
    public function __construct(private readonly OMDbAPI $omdbAPI, private readonly int $cacheTtl = 60)
    {
    }

    public function findMoviesExt(string $titleSearch, int $page = 1): LengthAwarePaginator
    {
        $movies = Cache::remember(
            'OmdbSearchByTitle-' . $titleSearch . $page,
            $this->cacheTtl,
            fn() => $this->omdbAPI->findMovieByTitle($titleSearch, $page)
        );
        $extIds = collect($movies->data)->map(fn(MovieShort $movie) => $movie->extId->toString());
        $ratings = $this->getRatingsByExtId($extIds, MovieSourceType::OMDb);
        $data = collect($movies->data)->map(
            fn(MovieShort $movie) => new MovieRatedShort($ratings->get($movie->extId->toString()), $movie)
        );
        return new LengthAwarePaginator($data, $movies->total, 10, $page);
    }

    public function movieDetailsFromExt(string $extId): ?MovieDetailsDTO
    {
        $movieDetails = Cache::remember($extId, $this->cacheTtl, fn() => $this->omdbAPI->movieById($extId));
        if ($movieDetails === null) {
            return null;
        }
        $movieRating = $this->getMovieByExtId($extId);
        return MovieDetailsDTO::fromExtDetails($movieRating, $movieDetails);
    }

    public function findRatedMovie(
        string $titleSearch,
        MovieSourceType $sourceType = MovieSourceType::OMDb
    ): LengthAwarePaginator {
        $searchPattern = implode(' ', array_map(fn ($value) => $value . '*', explode(' ', $titleSearch)));
        $movies = Movie::query()
                       ->withAvg('ratings as ratings_avg', 'rating')
                       ->when(
                           !empty($titleSearch),
                           fn($builder) => $builder->withWhereHas(
                               'sources',
                               fn($query) => $query
                                   ->whereRaw('MATCH (title) AGAINST (? IN BOOLEAN MODE)', [$searchPattern])
                                   ->where('source', $sourceType->value)
                           )
                       )
                       ->paginate(20);
        $movies->setCollection(
            $movies->map(
                fn(Movie $movie) => new MovieRatedShort(
                    $movie->ratings_avg,
                    MovieShort::fromSource($movie->sources()->first())
                )
            )
        );
        return $movies;
    }

    public function getAvgRatingByExtId(string $extId, MovieSourceType $sourceType = MovieSourceType::OMDb): ?float
    {
        $movie = $this->getMovieByExtId($extId);
        return $movie ? (float)$movie->avg_rating : null;
    }

    public function ratingForUserAndMovie(string|int $userId, string|int $movieId): ?int
    {
        return MovieRating::query()->where('movie_id', $movieId)->where('user_id', $userId)->first()?->rating;
    }

    private function getMovieByExtId(string $extId, MovieSourceType $sourceType = MovieSourceType::OMDb): ?Movie
    {
        return Movie::query()
                    ->withWhereHas(
                        'sources',
                        fn($builder) => $builder->where('source', $sourceType->value)->where('source_id', $extId)
                    )
                    ->first();
    }

    private function getRatingsByExtId(Collection $extIds, MovieSourceType $sourceType): Collection
    {
        return Movie::query()
                    ->withWhereHas(
                        'sources',
                        fn($builder) => $builder->where('source', $sourceType->value)->whereIn('source_id', $extIds)
                    )
                    ->get()
                    ->mapWithKeys(fn(Movie $movie) => [$movie->sources->first()->source_id => $movie->avg_rating]);
    }
}
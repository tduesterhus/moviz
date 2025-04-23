<?php

namespace App\Livewire\Movies;

use App\Objects\ExtMovieId;
use App\Objects\FiveStarRating;
use App\Queries\MovieQuery;
use App\Services\MovieRatingService;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class MovieList extends Component
{
    use WithPagination;

    #[Url]
    public string $searchString = '';

    #[Validate('required|integer|between:0,5')]
    public ?int $rating = null;

    public $movieDetails;
    public $source;

    public function mount(string $source)
    {
        $this->source = $source;
    }

    public function viewMovieDetails($extId, MovieQuery $movieQuery)
    {
        $this->movieDetails = $movieQuery->movieDetailsFromExt($extId);
        if ($this->movieDetails?->movieId !== null) {
            $this->rating = $movieQuery->ratingForUserAndMovie(auth()->id(), $this->movieDetails->movieId);
        }
    }

    public function detailsModalClosed()
    {
        $this->movieDetails = null;
        $this->rating       = null;
    }

    public function rateMovie(MovieRatingService $movieRatingService, MovieQuery $searchService)
    {
        $this->validate();
        $movieRatingService->rate(
            new ExtMovieId($this->movieDetails->extId),
            auth()->id(),
            new FiveStarRating($this->rating)
        );
        $this->movieDetails = $searchService->movieDetailsFromExt($this->movieDetails->extId);
        $this->dispatch('movie-rated.' . $this->movieDetails->extId);
    }

    public function render(MovieQuery $movieQuery)
    {
        $movies = match ($this->source) {
            'extern' => $movieQuery->findMoviesExt($this->searchString, $this->getPage()),
            default => $movieQuery->findRatedMovie($this->searchString)
        };
        return view(view: 'livewire.movies.movie-list', data: [
            'movies_paginated' => $movies,
        ]);
    }
}

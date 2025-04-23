<?php

namespace App\Livewire\Movies;

use App\Objects\MovieSourceType;
use App\Queries\MovieQuery;
use Livewire\Attributes\On;
use Livewire\Component;

class MovieListItem extends Component
{

    public $extId;
    public $title;
    public $year;
    public $image_url;
    public $type;
    public $avg_rating;

    public function mount(\App\Objects\MovieListItem $movie)
    {
        $this->extId = $movie->movieShort->extId->toString();
        $this->title = $movie->movieShort->title;
        $this->year = $movie->movieShort->year;
        $this->type = $movie->movieShort->type->translate();
        $this->image_url = $movie->movieShort->imageUrl;
        $this->avg_rating = $movie->avgRating ? (float)$movie->avgRating : null;
    }

    #[On('movie-rated.{extId}')]
    public function updateRating(MovieQuery $movieQuery)
    {
        $this->avg_rating = (float)$movieQuery->getMovieByExtId($this->extId, MovieSourceType::OMDb)?->avg_rating;
    }

    public function render()
    {
        return view('livewire.movies.movie-list-item');
    }
}

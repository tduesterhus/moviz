<?php

use App\APIs\OMDbAPI;
use App\Objects\ExtMovieId;
use App\Objects\FiveStarRating;
use App\Services\MovieRatingService;
use Illuminate\Support\Facades\Http;

test('rates successful existing movie', function () {
    $extId = 'tt2015381';

    $movieDetails = loadJsonFixture(sprintf('omdb/%s.json', $extId));
    Http::fake([
        'www.omdbapi.com/*' => Http::sequence()->push($movieDetails)
    ])->preventingStrayRequests();

    $user   = \App\Models\User::factory()->create();
    $others = \App\Models\User::factory()->count(2)->create();

    \App\Models\Movie::factory()
                     ->has(\App\Models\MovieSource::factory()->state(['source_id' => $extId]), 'sources')
                     ->has(
                         \App\Models\MovieRating::factory()->count(3)->state(
                             new \Illuminate\Database\Eloquent\Factories\Sequence(
                                 ['user_id' => $user->id, 'rating' => 1],
                                 ['user_id' => $others->get(0)->id, 'rating' => 3],
                                 ['user_id' => $others->get(1)->id, 'rating' => 2],
                             )
                         ),
                         'ratings'
                     )
                     ->create(['avg_rating' => 2]);

    $ratingService = new MovieRatingService(omdbQuery: app()->make(OMDbAPI::class));
    $ratingService->rate(new ExtMovieId($extId), $user->id, new FiveStarRating(4));

    $movie =
        \App\Models\Movie::query()
                         ->withWhereHas('sources', fn($builder) => $builder->where('source_id', $extId))
                         ->firstOrFail();

    $source = $movie->sources()->firstOrFail();
    \PHPUnit\Framework\assertEquals($source->title, $movieDetails['Title']);
    \PHPUnit\Framework\assertEquals($source->year, $movieDetails['Year']);
    \PHPUnit\Framework\assertEquals($source->type->value, $movieDetails['Type']);
    \PHPUnit\Framework\assertEquals($source->image_url, $movieDetails['Poster']);

    \PHPUnit\Framework\assertEquals('3.00', $movie->avg_rating);
});
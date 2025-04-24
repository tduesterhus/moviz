<?php

use App\Livewire\Movies\MovieList;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();

    $extId        = 'tt2015381';
    $searchData   = loadJsonFixture('omdb/omdb_search_galaxy.json');
    $movieDetails = loadJsonFixture(sprintf('omdb/%s.json', $extId));
    Http::fake([
        'www.omdbapi.com/*' => Http::sequence()
                                   ->push($searchData)
                                   ->push($movieDetails)
                                   ->push($movieDetails)
    ])->preventingStrayRequests();

    $livewireTest = Livewire::actingAs($user)
                            ->withQueryParams(['searchString' => 'galaxy'])
                            ->test(MovieList::class, ['source' => 'extern'])
                            ->assertStatus(200);

    foreach ($searchData['Search'] as $movie) {
        $livewireTest->assertSee($movie['Title']);
    }

    $livewireTest->call('viewMovieDetails', $searchData['Search'][0]['imdbID']);

    $livewireTest->assertSee($movieDetails['Title']);
    $livewireTest->assertSee($movieDetails['Plot']);
    $livewireTest->assertSee($movieDetails['Year']);
    $livewireTest->assertSee($movieDetails['Type']);

    $livewireTest->set('rating', 5);
    $livewireTest->call('rateMovie');

    \PHPUnit\Framework\assertTrue(
        \App\Models\Movie::query()->whereHas('sources', fn($builder) => $builder->where('source_id', $extId))->exists()
    );
});

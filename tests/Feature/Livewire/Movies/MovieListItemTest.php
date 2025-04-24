<?php

use App\Livewire\Movies\MovieListItem;
use Livewire\Livewire;

it('renders successfully', function () {
    $movie = \App\Models\Movie::factory()
                              ->has(\App\Models\MovieSource::factory(), 'sources')
                              ->create(['avg_rating' => 2]);

    $movieShort = \App\Objects\MovieShort::fromSource($movie->sources->firstOrFail());
    Livewire::test(MovieListItem::class, ['movie' => new \App\Objects\MovieListItem($movie->avg_rating, $movieShort)])
            ->assertStatus(200)
            ->assertSee($movieShort->title)
            ->assertSee($movieShort->year)
            ->assertSee($movieShort->type->translate())
            ->assertSee($movieShort->imageUrl);
});

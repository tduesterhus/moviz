<div class="flex flex-col gap-4 p-8 pl-24 pr-24">
    <div>
        <form>
            <flux:input label="Search" wire:model="searchString" wire:keydown.enter="$refresh" type="text"
                        class="max-w-1/2"/>
        </form>
    </div>
    <div class="flex flex-col gap-4">
        @foreach($movies_paginated as $movie)
            <livewire:movies.movie-list-item :movie="$movie" :key="$movie->movieShort->extId->toString()"/>
        @endforeach
    </div>
    <div class="max-w-3/4">
        {{ $movies_paginated->links() }}
    </div>
    <flux:modal name="movie-details" @close="detailsModalClosed">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold">{{ $movieDetails?->title }}</h1>
            <div class="flex flex-row gap-2">
                <div class="flex flex-col gap-2">
                    <img class="min-w-32" src="{{ $movieDetails?->imageUrl }}"/>
                    <span class="text-xs font-light">{{ $movieDetails?->type }} ({{ $movieDetails?->year }})</span>
                </div>
                <p>{{ $movieDetails?->plot }}</p>
            </div>

            <div class="flex content-center gap-2">
            <span class="text-center">Average Rating:
                @if($movieDetails?->avgRating)
                    {{ $movieDetails?->avgRating }} of 5
                @else
                    N.A.
                @endif
            </span>
                <flux:icon.star variant="solid" class="text-amber-500 dark:text-amber-300"/>
            </div>
        </div>
        <flux:separator class="my-4"/>
        <form wire:submit="rateMovie">
            <div class="flex flex-col gap-2">
                <flux:select class="max-w-16" label="{{__('Your rating')}}" wire:model="rating"
                             placeholder="reach for the stars...">
                    <flux:select.option value="1">0</flux:select.option>
                    <flux:select.option value="1">1</flux:select.option>
                    <flux:select.option value="2">2</flux:select.option>
                    <flux:select.option value="3">3</flux:select.option>
                    <flux:select.option value="4">4</flux:select.option>
                    <flux:select.option value="5">5</flux:select.option>
                </flux:select>
                <flux:button class="max-w-24" type="submit">Submit</flux:button>
            </div>
        </form>
    </flux:modal>
</div>

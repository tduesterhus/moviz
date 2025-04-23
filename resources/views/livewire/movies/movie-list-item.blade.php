<div class="flex flex-row gap-8" xmlns:flux="http://www.w3.org/1999/html">
    <div class="flex justify-center min-w-48">
        <img class="h-48" src="{{ $image_url }}"/>
    </div>
    <div class="flex flex-col justify-center">
        <span class="text-xl">{{ $title }}</span>
        <span class="text-sm">{{ $year }}</span>
        <span>{{ $type }}</span>
        <div class="flex content-center gap-2">
            <span class="text-center">
                @if($avg_rating)
                    {{ $avg_rating }} of 5
                @else
                    N/A
                @endif
            </span>
            <flux:icon.star variant="solid" class="text-amber-500 dark:text-amber-300"/>
        </div>
        <flux:modal.trigger name="movie-details">
            <flux:button class="w-32" type="button" wire:click="$parent.viewMovieDetails('{{ $extId }}')">View</flux:button>
        </flux:modal.trigger>
    </div>
</div>
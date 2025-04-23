<?php

declare(strict_types=1);

namespace App\Objects;

use Illuminate\Support\Facades\Log;

enum MovieType: string
{
    case Movie = 'movie';
    case Series = 'series';
    case Episode = 'episode';
    case Game = 'game';
    case Unknown = 'unknown';

    public static function fromString(string $type): MovieType
    {
        $result = MovieType::tryFrom($type);
        if ($result === null) {
            Log::warning('[OMDb] unknown movie type: ' . $type);
            return MovieType::Unknown;
        }
        return $result;
    }

    public function translate(): string
    {
        return __('movies.types.'.$this->value);
    }
}
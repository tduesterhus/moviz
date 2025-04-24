<?php

namespace App\Models;

use App\Objects\MovieType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'uuid'];

    protected function casts(): array
    {
        return [
            'type' => MovieType::class,
        ];
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(MovieRating::class);
    }

    public function sources(): HasMany
    {
        return $this->hasMany(MovieSource::class);
    }
}

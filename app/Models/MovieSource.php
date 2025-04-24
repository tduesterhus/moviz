<?php

namespace App\Models;

use App\Objects\MovieSourceType;
use App\Objects\MovieType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovieSource extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function casts(): array
    {
        return [
            'source' => MovieSourceType::class,
            'type' => MovieType::class,
        ];
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}

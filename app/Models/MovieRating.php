<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovieRating extends Model
{
    protected $guarded = ['id'];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}

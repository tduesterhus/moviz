<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movie_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Movie::class)->index();
            $table->string('source')->index();
            $table->string('source_id')->index();
            $table->string('title')->fulltext();
            $table->string('year');
            $table->string('image_url');
            $table->string('type');
            $table->timestamps();
            $table->unique(['source', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_sources');
    }
};

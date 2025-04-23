<?php

namespace Database\Seeders;

use App\Models\User;
use App\Objects\FiveStarRating;
use App\Objects\IMDbIdExt;
use App\Queries\OMDbAPIQuery;
use App\Services\MovieRatingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MovieSeeder extends Seeder
{
    public function __construct(private readonly OMDbAPIQuery $query, private readonly MovieRatingService $rateService)
    {

    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = [
            'test@example.com',
            'hello@example.com',
            'rudolf@example.com',
            'ester@example.com',
            'max@example.com',
        ];
        foreach($emails as $email) {
            User::query()->firstOrCreate(['email' => $email], ['name' => 'Test User', 'password' => Hash::make('password')]);
        }

        $extIds = [
            'tt2015381',
            'tt0108778',
            'tt0120657',
            'tt13929916',
            'tt3566834',
        ];

        foreach(User::all() as $user) {
            foreach($extIds as $extId) {
                $this->rateService->rate(new IMDbIdExt($extId), $user->id, new FiveStarRating(random_int(1,5)));
            }
        }
    }
}

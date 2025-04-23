<?php

namespace App\Console\Commands;

use App\Objects\MovieSourceType;
use App\Queries\MovieRatingQuery;
use App\Queries\OMDbAPIQuery;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(OMDbAPIQuery $query, MovieRatingQuery $movieRatingQuery)
    {
        $result = $movieRatingQuery->ratingsByExternalId(collect([
            'tt2015381',
            'tt0108778',
            'tt0120657',
            'tt13929916',
            'tt3566834',
            'tt2011970',
        ]), MovieSourceType::OMDb);

        dump($result);
    }
}

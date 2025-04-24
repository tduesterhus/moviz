<?php

declare(strict_types=1);

namespace App\APIs;

use App\Exceptions\OMDbAPIException;
use App\Objects\MovieDetails;
use App\Objects\ExtMovieId;
use App\Objects\MovieShort;
use App\Objects\MovieShortList;
use App\Objects\MovieSourceType;
use App\Objects\MovieType;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OMDbAPI
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiKey
    ) {
    }

    public function findMovieByTitle(string $titleSearch, int $page = 1): MovieShortList
    {
        if (empty($titleSearch)) {
            return new MovieShortList([], MovieSourceType::OMDb, 0, 1);
        }

        $options  = [
            's'    => $titleSearch,
            'page' => $page,
        ];
        $response = $this->get($options);

        $data = collect($response['Search'] ?? [])->map(fn(array $movieArray) => new MovieShort(
            extId: new ExtMovieId($movieArray['imdbID']),
            title: $movieArray['Title'],
            type: MovieType::fromString($movieArray['Type']),
            year: $movieArray['Year'],
            imageUrl: $movieArray['Poster'],
        ));

        return new MovieShortList($data->all(), MovieSourceType::OMDb, (int)$response['totalResults'] ?? 0, $page);
    }

    public function movieById(string $omdbId): ?MovieDetails
    {
        $options = [
            'i'    => $omdbId,
            'plot' => 'full'
        ];
        try {
            $response    = $this->get($options);
            $releaseDate = null;
            try {
                $releaseDate = CarbonImmutable::parse($response['Released']);
            } catch (InvalidFormatException $exception) {
            }
            return new MovieDetails(
                source: MovieSourceType::OMDb,
                movieId: new ExtMovieId($response['imdbID']),
                title: $response['Title'],
                type: MovieType::fromString($response['Type']),
                year: (int)$response['Year'],
                releaseDate: $releaseDate,
                runtime: $response['Runtime'],
                genre: $response['Genre'],
                director: $response['Director'],
                writer: $response['Writer'],
                actors: $response['Actors'],
                plot: $response['Plot'],
                country: $response['Country'],
                language: $response['Language'],
                awards: $response['Awards'],
                imageUrl: $response['Poster'],
            );
        } catch (OMDbAPIException $e) {
            return null;
        }
    }

    private function get(array $options): array
    {
        $options  = array_merge($options, [
            'apikey' => $this->apiKey,
            'r'      => 'json'
        ]);
        $response = Http::baseUrl($this->baseUrl)->get('/', $options);
        return $this->handleResponse($response);
    }

    private function handleResponse(PromiseInterface|Response $response)
    {
        if ($response->failed()) {
            throw new OMDbAPIException();
        }
        return $response->json();
    }
}
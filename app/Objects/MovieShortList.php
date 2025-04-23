<?php

declare(strict_types=1);

namespace App\Objects;

class MovieShortList
{

    /**
     * @param array<MovieShort> $data
     * @param MovieSourceType $source
     * @param int $total
     * @param int $page
     */
    public function __construct(
        public readonly array $data,
        public readonly MovieSourceType $source,
        public readonly int $total,
        public readonly int $page
    ) {
    }

}
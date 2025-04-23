<?php
declare(strict_types=1);

namespace App\Objects;

enum MovieSourceType: string
{
    case OMDb = 'omdb';
}
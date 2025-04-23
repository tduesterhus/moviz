<?php

return [
    'types' => [
        \App\Objects\MovieType::Movie->value => 'Movie',
        \App\Objects\MovieType::Unknown->value => 'Unknown',
        \App\Objects\MovieType::Episode->value => 'Episode',
        \App\Objects\MovieType::Series->value => 'Series',
        \App\Objects\MovieType::Game->value => 'Game',
    ],
];

<?php

namespace App\Services\Servers;

class RandomWordService
{
    private const RANDOM_WORDS = [
        'robin', 'seagull', 'pigeon', 'blue-jay', 'vulture', 'finch', 'falcon', 'phoenix', 'squirrel', 'parrot', 'hawk',
        'sparrow', 'owl', 'swan', 'dove', 'cardinal', 'cow', 'penguin', 'chupacabra', 'spoonbill', 'humming', 'turkey',
        'chicken', 'junco', 'eagle', 'woodpecker', 'mockingbird', 'grackle', 'lovebird', 'bluebird', 'magpie', 'starling',
        'cockatiel', 'swallow', 'grosbeak', 'goose', 'forpus', 'budgerigar', 'mango', 'towhee', 'warbler', 'peregrine',
        'nuthatch', 'chickadee', 'bananaquit', 'crow', 'raven', 'merlin', 'spatuletail',
    ];

    public function word(): string
    {
        return array_random(self::RANDOM_WORDS);
    }
}

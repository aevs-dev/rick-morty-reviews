<?php

namespace App\Service;

use NickBeen\RickAndMortyPhpApi\Episode;
use NickBeen\RickAndMortyPhpApi\Exceptions\NotFoundException;

class RickAndMortyService
{
    private Episode $episode; // Object to get episodes info
    public function __construct()
    {
        $this->episode = new Episode();
    }

    public function getEpisodeById(int $episodeId): array|null
    {
        try {
            $result = $this->episode->get($episodeId);
        } catch (NotFoundException $notFoundException) {
            return null;
        }

        return (array)$result;

    }
}
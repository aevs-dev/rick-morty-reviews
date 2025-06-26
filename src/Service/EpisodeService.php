<?php

namespace App\Service;

use App\Repository\ReviewRepository;

class EpisodeService
{

    public function __construct(
        private readonly ReviewRepository $reviewRepository,
        private readonly RickAndMortyService $rickAndMortyService
    )
    {
    }

    public function getEpisodeDescription(int $episodeId): array
    {
        $episode = $this->rickAndMortyService->getEpisodeById($episodeId);
        if (!$episode) return ['status' => 'error', 'code' => 404, 'message' => 'This episode does not exist!'];

        $avgRating = round($this->reviewRepository->getAverageRatingOfEpisode($episodeId), 2);
        $lastReviews = $this->reviewRepository->getReviewsByEpisode($episodeId);

        $data = [
            'id' => $episode['id'], 'name' => $episode['name'], 'air_date' => $episode['air_date'],
            'average_rating' => $avgRating, 'last_reviews' => $lastReviews
        ];

        return ['status' => 'success', 'code' => 200, 'data' => $data];
    }

}
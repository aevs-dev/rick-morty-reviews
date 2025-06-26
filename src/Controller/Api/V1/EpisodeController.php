<?php

namespace App\Controller\Api\V1;

use App\Service\EpisodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class EpisodeController extends AbstractController
{


    public function __construct(
        private readonly EpisodeService $episodeService
    )
    {
    }

    #[Route('/episode/{episode_id}', name: 'get_episode', requirements: ['episode_id' => Requirement::DIGITS], methods: ['GET'])]
    public function index(int $episode_id): JsonResponse
    {
        $data = $this->episodeService->getEpisodeDescription($episode_id);
        if ($data['status'] == 'error') return $this->json($data, $data['code']);

        return $this->json($data['data']);
    }
}

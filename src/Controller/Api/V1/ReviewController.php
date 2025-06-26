<?php

namespace App\Controller\Api\V1;

use App\Dto\CreateReviewDto;
use App\Dto\DtoFactory;
use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ReviewController extends AbstractController
{

    public function __construct(
        private readonly ReviewService $reviewService,
        private readonly DtoFactory    $dtoFactory,
    )
    {
    }


    /**
     * Add new review to some episode
     */
    #[Route('/review', name: 'create_review', methods: ['POST'])]
    public function createReview(Request $request): JsonResponse
    {
        $bodyParams = json_decode($request->getContent(), true);

        /** @var CreateReviewDto $createReviewDto */
        $createReviewDto = $this->dtoFactory->createFromData($bodyParams, CreateReviewDto::class);
        $errors = $this->dtoFactory->validate($createReviewDto);
        if ($errors) return $this->json(['status' => 'validation_error', 'errors' => $errors], 400);

        $data = $this->reviewService->addReview($createReviewDto);
        if ($data['status'] == 'error') return $this->json($data, $data['code']);

        return $this->json($data['data'], 201);
    }
}

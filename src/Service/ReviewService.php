<?php

namespace App\Service;

use App\Dto\CreateReviewDto;
use App\Repository\ReviewRepository;
use Sentiment\Analyzer;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewService
{
    private Analyzer $sentimentAnalyzer;
    public function __construct(
        private readonly ReviewRepository $reviewRepository,
        private readonly SerializerInterface $serializer
    )
    {
        $this->sentimentAnalyzer = new Analyzer();
    }

    public function addReview(CreateReviewDto $createReviewDto): array
    {
        $reviewTextRating = $this->sentimentAnalyzer->getSentiment($createReviewDto->getReviewText());
        $reviewTextRating = ($reviewTextRating['compound'] + 1) / 2; // convert rating scale from "-1 to 1" to "0 to 1"


        $createReviewDto->setRating($reviewTextRating);
        $reviewEntity = $this->reviewRepository->createReview($createReviewDto);
        if (!$reviewEntity) return ['status' => 'error', 'code' => 500, 'message' => 'Failed to create a review due to an internal server error'];

        return [
            'status' => 'success',
            'code' => 201,
            'data' => $this->serializer->normalize($reviewEntity)
        ];

    }

}
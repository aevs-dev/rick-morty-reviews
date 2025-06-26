<?php

namespace App\Dto;

use App\Validator\EpisodeExists;
use Symfony\Component\Validator\Constraints as Assert;

class CreateReviewDto
{

    private float $rating;
    public function __construct(

        #[Assert\NotBlank(message: 'EpisodeId cannot be blank')]
        #[Assert\Type(type: 'integer', message: 'EpisodeId must be integer')]
        #[EpisodeExists]
        private ?int $episodeId,

        #[Assert\NotBlank(message: 'Username cannot be blank')]
        #[Assert\Length(min: 1, max: 32,
            minMessage: 'Username length must be greater than 1',
            maxMessage: 'Username length must be less than 32')]
        private ?string $username,

        #[Assert\NotBlank(message: 'Review text cannot be blank')]
        #[Assert\Length(min: 3, max: 1024,
            minMessage: 'Review text length must be greater than 3',
            maxMessage: 'Review text length must be less than 1024')]
        private ?string $reviewText,
    )
    {
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getReviewText(): string
    {
        return $this->reviewText;
    }

    /**
     * @param string $reviewText
     */
    public function setReviewText(string $reviewText): void
    {
        $this->reviewText = $reviewText;
    }

    /**
     * @return int|null
     */
    public function getEpisodeId(): ?int
    {
        return $this->episodeId;
    }

    /**
     * @param int $episodeId
     */
    public function setEpisodeId(int $episodeId): void
    {
        $this->episodeId = $episodeId;
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating(float $rating): void
    {
        $this->rating = $rating;
    }



}
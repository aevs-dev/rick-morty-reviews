<?php

namespace App\Dto;

class ReviewDto
{
    public function __construct(
        private int $id,
        private string $username,
        private string $reviewText,
    )
    {
    }

}
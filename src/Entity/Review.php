<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\Table(name: "reviews")]
#[ORM\HasLifecycleCallbacks]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $username = null;

    #[ORM\Column(length: 1024)]
    private ?string $review_text = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $rating = null;

    #[ORM\Column]
    private ?int $episode_id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    #[SerializedName('review_text')]
    public function getReviewText(): ?string
    {
        return $this->review_text;
    }

    public function setReviewText(string $review_text): static
    {
        $this->review_text = $review_text;

        return $this;
    }

    public function getRating(): ?float
    {
        return round($this->rating, 2);
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getEpisodeId(): ?int
    {
        return $this->episode_id;
    }

    public function setEpisodeId(int $episode_id): static
    {
        $this->episode_id = $episode_id;

        return $this;
    }

    #[SerializedName('created_at')]
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }
}

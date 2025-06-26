<?php

namespace App\Repository;

use App\Dto\CreateReviewDto;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, Review::class);
    }

    public function createReview(CreateReviewDto $createReviewDto): Review|bool
    {
        $review = new Review();

        $review->setUsername($createReviewDto->getUsername());
        $review->setReviewText($createReviewDto->getReviewText());
        $review->setRating($createReviewDto->getRating());
        $review->setEpisodeId($createReviewDto->getEpisodeId());

        $this->entityManager->persist($review);

        try {
            $this->entityManager->flush();
        } catch (ORMException $ORMException) {
            return false;
        }

        return $review;

    }

    public function getReviewsByEpisode(int $episodeId, int|null $limit = 3, array|null $orderBy = ['created_at' => 'DESC']): array
    {
        $queryBuilder = $this->createQueryBuilder('q')
            ->where('q.episode_id = :episode_id');

        if ($orderBy) $queryBuilder->addOrderBy('q.' . key($orderBy), current($orderBy));
        if ($limit) $queryBuilder->setMaxResults($limit);

        $queryBuilder->setParameter('episode_id', $episodeId);

        return $queryBuilder->getQuery()->getArrayResult();

    }


    public function getAverageRatingOfEpisode(int $episodeId): float
    {
        $query = $this->entityManager->createQuery(
            'SELECT COALESCE(AVG(rev.rating), 0) AS avg_rating
            FROM App\Entity\Review rev
            WHERE rev.episode_id = :episode_id'
        )->setParameter('episode_id', $episodeId);

        return $query->getSingleScalarResult();
    }

}

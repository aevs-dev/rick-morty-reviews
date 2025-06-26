<?php

namespace App\Validator;


use App\Service\RickAndMortyService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class EpisodeExistsValidator extends ConstraintValidator
{

    public function __construct(
        private readonly RickAndMortyService $rickAndMortyService
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!is_int($value)) return;
        $episodeInfo = $this->rickAndMortyService->getEpisodeById($value);

        if (!$episodeInfo) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

}
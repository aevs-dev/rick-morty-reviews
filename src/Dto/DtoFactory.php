<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoFactory
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function createFromData(array $data, string $dtoClass): object
    {
        return $this->serializer->deserialize(json_encode($data), $dtoClass, 'json');
    }

    public function validate(object $dto): array
    {
        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) return $this->formatErrors($errors);
        return [];
    }

    private function formatErrors(ConstraintViolationListInterface $errors): array
    {
        $formattedErrors = [];

        foreach ($errors as $error) {
            $formattedErrors[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }

        return $formattedErrors;
    }
}
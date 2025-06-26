<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class EpisodeExists extends Constraint
{

    public function __construct(mixed $options = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);
    }


    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }

}
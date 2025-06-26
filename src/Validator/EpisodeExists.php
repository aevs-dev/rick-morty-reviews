<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class EpisodeExists extends Constraint
{

    public string $message;

    public function __construct(string $message = 'Episode does not exist', mixed $options = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);

        $this->message = $message;
    }


    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }

}
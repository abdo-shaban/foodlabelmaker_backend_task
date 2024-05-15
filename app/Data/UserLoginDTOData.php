<?php

namespace App\Data;

use Spatie\LaravelData\Dto;

class UserLoginDTOData extends Dto
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}

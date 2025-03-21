<?php

namespace App\Infrastructure;

use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;

class EmployeeDTO
{
    public string $id;
    
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 255)]
        public string             $name,
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 255)]
        public string             $surname,
        #[Assert\NotBlank]
        #[Assert\Email]
        public string             $email,
        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual('today')]
        public DateTimeImmutable $dateStart,
        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual(100)]
        public float              $salary,
    )
    {
    }
}

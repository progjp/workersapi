<?php

declare(strict_types=1);

namespace App\Api\Transformer;

use App\Domain\Model\Employee;

class EmployeeTransformer
{
    public function transform(Employee $employee): array
    {
        return [
            "id"         => $employee->getId(),
            "name"       => $employee->getName(),
            "surname"    => $employee->getSurname(),
            "email"      => $employee->getEmail(),
            "date_start" => $employee->getDateStart(),
            "salary"     => $employee->getSalary(),
            "created_at" => $employee->getCreatedAt(),
            "updated_at" => $employee->getUpdatedAt(),
        ];
    }
}

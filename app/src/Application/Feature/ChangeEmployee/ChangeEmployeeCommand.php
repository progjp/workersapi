<?php

namespace App\Application\Feature\ChangeEmployee;
use App\Infrastructure\EmployeeDTO;

readonly class ChangeEmployeeCommand
{
    public function __construct(public string $id, public EmployeeDTO $employeeDTO) {
    }
}

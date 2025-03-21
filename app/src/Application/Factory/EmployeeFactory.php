<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Domain\Model\Employee;
use App\Infrastructure\EmployeeDTO;

class EmployeeFactory
{
    public function update(Employee $employee, EmployeeDTO $employeeDTO): Employee
    {
        return $employee->withUpdatedFields(
            name: $employeeDTO->name,
            surname: $employeeDTO->surname,
            email: $employeeDTO->email,
            dateStart: $employeeDTO->dateStart,
            salary: $employeeDTO->salary
        );
    }
}

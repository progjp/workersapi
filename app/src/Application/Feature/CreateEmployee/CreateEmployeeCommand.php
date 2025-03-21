<?php

namespace App\Application\Feature\CreateEmployee;

use App\Domain\Model\Employee;
use App\Infrastructure\EmployeeDTO;

class CreateEmployeeCommand
{
    private readonly Employee $employee;
    
    public function __construct(EmployeeDTO $employeeDTO)
    {
        $this->employee = new Employee(
            name: $employeeDTO->name,
            surname: $employeeDTO->surname,
            email: $employeeDTO->email,
            dateStart: $employeeDTO->dateStart,
            salary: $employeeDTO->salary,
        );
    }
    
    public function getEmployee(): Employee
    {
        return $this->employee;
    }
}

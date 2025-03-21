<?php

namespace App\Application\Feature\CreateEmployee;

use App\Domain\Model\Employee;
use App\Domain\Repository\EmployeeRepositoryInterface;

class CreateEmployeeCommandHandler
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository,
    )
    {
    }
    
    public function __invoke(CreateEmployeeCommand $command): Employee
    {
        $employee = $command->getEmployee();
        
        return $this->employeeRepository->save($employee);
    }
}

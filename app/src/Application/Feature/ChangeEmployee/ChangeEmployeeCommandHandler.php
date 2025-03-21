<?php

namespace App\Application\Feature\ChangeEmployee;

use App\Application\Factory\EmployeeFactory;
use App\Domain\Model\Employee;
use App\Domain\Repository\EmployeeRepositoryInterface;

readonly class ChangeEmployeeCommandHandler
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private EmployeeFactory             $factory,
    )
    {
    }
    
    public function __invoke(ChangeEmployeeCommand $command): ?Employee
    {
        $employee = $this->employeeRepository->findById($command->id);
        if(null === $employee) {
            return null;
        }
        
        $command->employeeDTO->id = $employee->getId();
        $updatedEmployee = $this->factory->update($employee, $command->employeeDTO);
        $this->employeeRepository->replace($employee, $updatedEmployee);
        
        return $employee;
    }
}

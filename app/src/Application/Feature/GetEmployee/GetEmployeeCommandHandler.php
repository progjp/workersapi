<?php

namespace App\Application\Feature\GetEmployee;

use App\Domain\Model\Employee;
use App\Domain\Repository\EmployeeRepositoryInterface;

readonly class GetEmployeeCommandHandler
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
    )
    {
    }
    
    public function __invoke(GetEmployeeCommand $command): ?Employee
    {
        return $this->employeeRepository->findById($command->id);
    }
}

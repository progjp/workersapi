<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Employee;

interface EmployeeRepositoryInterface
{
    public function save(Employee $employee): Employee;
    public function replace(Employee $oldEmployee, Employee $newEmployee): Employee;
    
    public function findById(string $id): ?Employee;
    public function findAll(): array;
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Db\Doctrine;

use App\Domain\Model\Employee;
use App\Domain\Repository\EmployeeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Employee $employee): Employee
    {
        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $employee;
    }
    
    public function replace(Employee $oldEmployee, Employee $newEmployee): Employee
    {
        $this->entityManager->remove($oldEmployee);
        $this->entityManager->persist($newEmployee);
        $this->entityManager->flush();
        
        return $newEmployee;
    }
    
    public function findById(string $id): ?Employee
    {
        return $this->entityManager->getRepository(Employee::class)->find($id);
    }
    
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Employee::class)->findAll();
        
    }
}

<?php

namespace App\Application\Feature\DeleteEmployee;

use App\Domain\Model\Employee;
use Doctrine\ORM\EntityManagerInterface;

readonly class DeleteEmployeeCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }
    
    public function __invoke(Employee $employee): void
    {
        $this->entityManager->remove($employee);
        $this->entityManager->flush();
    }
}

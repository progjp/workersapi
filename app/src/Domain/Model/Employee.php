<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PreUpdate;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\CustomIdGenerator;

#[ORM\Entity]
#[UniqueEntity('email', message: 'Email already exists')]
#[ORM\HasLifecycleCallbacks]
class Employee
{
    #[Id]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[Column(type: 'uuid', unique: true)]
    #[CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;
    
    public function __construct(
        #[Column(type: 'string', length: 255)]
        #[Assert\NotBlank]
        private string            $name,
        #[Column(type: 'string', length: 255)]
        #[Assert\NotBlank]
        private string            $surname,
        
        #[Column(name: 'email', type: 'string', length: 255, unique: true)]
        #[Assert\NotBlank]
        #[Assert\Email]
        private string            $email,
        
        #[Column(type: 'datetime_immutable')]
        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual('today')]
        private DateTimeImmutable $dateStart,
        
        #[Column(type: 'float')]
        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual(100)]
        private float             $salary
    )
    {
        $this->createdAt = new DateTimeImmutable();
    }
    
    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;
    
    #[Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $updatedAt;
    
    #[PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
    
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = $this->createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
    
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    public function setId($id): self
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getSurname(): string
    {
        return $this->surname;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function getDateStart(): DateTimeImmutable
    {
        return $this->dateStart;
    }
    
    public function getSalary(): float
    {
        return $this->salary;
    }
    
    public function withUpdatedFields(
        ?string             $name = null,
        ?string             $surname = null,
        ?string             $email = null,
        ?\DateTimeImmutable $dateStart = null,
        ?float              $salary = null
    ): Employee
    {
        $this->name = $name ?? $this->name;
        $this->surname = $surname ?? $this->surname;
        $this->email = $email ?? $this->email;
        $this->dateStart = $dateStart ?? $this->dateStart;
        $this->salary = $salary ?? $this->salary;
        
        return $this;
    }
}

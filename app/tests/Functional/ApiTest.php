<?php

namespace App\Tests\Functional;

use App\Domain\Model\Employee;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ApiTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        $this->entityManager->close();
    }
    
    public function testCreate(): void
    {
        $faker = Factory::create();
        $name = $faker->name;
        $surname = $faker->lastName;
        $email = $faker->email;
        $salary = $faker->randomFloat(2, 110, 1000);
        $dateStart = $faker->dateTimeBetween('+0 days', '+2 years')->format('Y-m-d');
        
        $fakerData = [
            "name"      => $name,
            "surname"   => $surname,
            "email"     => $email,
            "salary"    => $salary,
            "dateStart" => $dateStart,
        ];
        $requestJson = json_encode($fakerData, JSON_THROW_ON_ERROR);
        
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/employee/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $requestJson
        );
        $response = $client->getResponse();
        $responseContent = json_decode($response->getContent());
        self::assertObjectHasProperty('id', $responseContent);
        self::assertObjectHasProperty('name', $responseContent);
        self::assertObjectHasProperty('surname', $responseContent);
        self::assertObjectHasProperty('email', $responseContent);
        self::assertObjectHasProperty('date_start', $responseContent);
        self::assertObjectHasProperty('salary', $responseContent);
        self::assertObjectHasProperty('created_at', $responseContent);
        self::assertObjectHasProperty('updated_at', $responseContent);
        $record = $this->entityManager
            ->getRepository(Employee::class)
            ->find($responseContent->id);
        self::assertNotNull($record);
        self::assertEquals($name, $record->getName());
        self::assertEquals($surname, $record->getSurname());
        self::assertEquals($email, $record->getEmail());
        self::assertEquals($salary, $record->getSalary());
        self::assertResponseStatusCodeSame(201);
    }
    
    public function testUpdate(): void
    {
        $faker = Factory::create();
        $name = $faker->name;
        $surname = $faker->lastName;
        $email = $faker->email;
        $salary = $faker->randomFloat(2, 110, 1000);
        $dateStart = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+0 days', '+2 years'));
        
        $fakerData = [
            "name"      => $name,
            "surname"   => $surname,
            "email"     => $email,
            "salary"    => $salary,
            "dateStart" => $dateStart->format('Y-m-d'),
        ];
        
        $testEntity = new Employee(
            name: $name,
            surname: $surname,
            email: $email,
            dateStart: $dateStart,
            salary: $salary,
        );
        $this->entityManager->persist($testEntity);
        $this->entityManager->flush();
        
        $requestJson = json_encode($fakerData, JSON_THROW_ON_ERROR);
        
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/employee/' . $testEntity->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $requestJson
        );
        $response = $client->getResponse();
        $responseContent = json_decode($response->getContent());
        self::assertObjectHasProperty('name', $responseContent);
        self::assertObjectHasProperty('surname', $responseContent);
        self::assertObjectHasProperty('email', $responseContent);
        self::assertObjectHasProperty('date_start', $responseContent);
        self::assertObjectHasProperty('salary', $responseContent);
        self::assertObjectHasProperty('created_at', $responseContent);
        self::assertObjectHasProperty('updated_at', $responseContent);
        $record = $this->entityManager
            ->getRepository(Employee::class)
            ->find($responseContent->id);
        self::assertNotNull($record);
        self::assertEquals($name, $record->getName());
        self::assertEquals($surname, $record->getSurname());
        self::assertEquals($email, $record->getEmail());
        self::assertEquals($salary, $record->getSalary());
        self::assertResponseStatusCodeSame(200);
    }
    
    public function testIndex(): void
    {
        $faker = Factory::create();
        $name = $faker->name;
        $surname = $faker->lastName;
        $email = $faker->email;
        $salary = $faker->randomFloat(2, 110, 1000);
        $dateStart = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+0 days', '+2 years'));
        
        $testEntity = new Employee(
            name: $name,
            surname: $surname,
            email: $email,
            dateStart: $dateStart,
            salary: $salary,
        );
        $this->entityManager->persist($testEntity);
        $this->entityManager->flush();
        
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/api/employee/' . $testEntity->getId());
        
        self::assertResponseStatusCodeSame(200);
        $response = $client->getResponse();
        $responseContent = json_decode($response->getContent());
        self::assertObjectHasProperty('id', $responseContent);
        self::assertObjectHasProperty('name', $responseContent);
        self::assertObjectHasProperty('surname', $responseContent);
        self::assertObjectHasProperty('email', $responseContent);
        self::assertObjectHasProperty('date_start', $responseContent);
        self::assertObjectHasProperty('salary', $responseContent);
        self::assertObjectHasProperty('created_at', $responseContent);
        self::assertObjectHasProperty('updated_at', $responseContent);
        self::assertSame($name, $responseContent->name);
        self::assertSame($surname, $responseContent->surname);
        self::assertSame($email, $responseContent->email);
        self::assertSame($salary, (float)$responseContent->salary);
        self::assertSame($dateStart->format('Y-m-d'), new DateTimeImmutable($responseContent->date_start->date)->format('Y-m-d'));
        self::assertSame(new \DateTime()->format('Y-m-d'), new DateTimeImmutable($responseContent->created_at->date)->format('Y-m-d'));
        self::assertSame(new \DateTime()->format('Y-m-d'), new DateTimeImmutable($responseContent->updated_at->date)->format('Y-m-d'));
        self::assertResponseIsSuccessful();
    }
    
    public function testDelete(): void
    {
        $faker = Factory::create();
        $name = $faker->name;
        $surname = $faker->lastName;
        $email = $faker->email;
        $salary = $faker->randomFloat(2, 110, 1000);
        $dateStart = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+0 days', '+2 years'));
        
        $testEntity = new Employee(
            name: $name,
            surname: $surname,
            email: $email,
            dateStart: $dateStart,
            salary: $salary,
        );
        $this->entityManager->persist($testEntity);
        $this->entityManager->flush();
        
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('DELETE', '/api/employee/' . $testEntity->getId());
        self::assertResponseStatusCodeSame(204);
        
        $record = $this->entityManager
            ->getRepository(Employee::class)
            ->find($testEntity->getId());
        self::assertNull($record);
    }
}

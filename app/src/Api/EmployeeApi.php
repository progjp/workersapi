<?php

namespace App\Api;

use App\Api\Transformer\EmployeeTransformer;
use App\Application\Feature\ChangeEmployee\ChangeEmployeeCommand;
use App\Application\Feature\ChangeEmployee\ChangeEmployeeCommandHandler;
use App\Application\Feature\CreateEmployee\CreateEmployeeCommand;
use App\Application\Feature\CreateEmployee\CreateEmployeeCommandHandler;
use App\Application\Feature\DeleteEmployee\DeleteEmployeeCommandHandler;
use App\Application\Feature\GetEmployee\GetEmployeeCommand;
use App\Application\Feature\GetEmployee\GetEmployeeCommandHandler;
use App\Infrastructure\EmployeeDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api/employee')]
final class EmployeeApi extends AbstractController
{
    #[Route('/', name: 'api_employee', methods: ['POST'])]
    #[OA\RequestBody(content: new OA\JsonContent(type: EmployeeDTO::class,
        example: [
            "name"      => "string",
            "surname"   => "string",
            "email"     => "string",
            "dateStart" => "2025-03-21T07:31:05.571Z",
            "salary"    => 100.25,
        ]))]
    #[OA\Response(
        response: 201,
        description: 'Create employee',
    )]
    public function new(#[MapRequestPayload] EmployeeDTO $employeeDTO, CreateEmployeeCommandHandler $handler): JsonResponse
    {
        $command = new CreateEmployeeCommand($employeeDTO);
        $employee = $handler($command);
        
        return new JsonResponse((new EmployeeTransformer())->transform($employee), Response::HTTP_CREATED);
    }
    
    #[Route('/{id}', name: 'api_employee_show', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get(show) employee',
    )]
    public function show(string $id, GetEmployeeCommandHandler $handler): JsonResponse
    {
        $command = new GetEmployeeCommand($id);
        $employee = $handler($command);
        if ($employee === null) {
            return new JsonResponse(json_encode(["message" => "Employee doesn't exist"],
                JSON_PRETTY_PRINT),Response::HTTP_NOT_FOUND);
        }
        
        return new JsonResponse((new EmployeeTransformer())->transform($employee), Response::HTTP_OK);
    }
    
    #[Route('/{id}', name: 'api_employee_edit', methods: ['PUT'])]
    #[OA\RequestBody(content: new OA\JsonContent(type: EmployeeDTO::class,
        example: [
            "name"      => "string",
            "surname"   => "string",
            "email"     => "string",
            "dateStart" => "2025-03-21T07:31:05.571Z",
            "salary"    => 100.25,
        ]))]
    #[OA\Response(
        response: 200,
        description: 'Update employee',
    )]
    public function edit(string                           $id,
                         #[MapRequestPayload] EmployeeDTO $employeeDTO,
                         ChangeEmployeeCommandHandler     $handler): JsonResponse
    {
        $command = new ChangeEmployeeCommand($id, $employeeDTO);
        $employee = $handler($command);
        if ($employee === null) {
            return new JsonResponse(json_encode(["message" => "Employee doesn't exist"], JSON_PRETTY_PRINT),
                Response::HTTP_NOT_FOUND
            );
        }
        
        return new JsonResponse((new EmployeeTransformer())->transform($employee), Response::HTTP_OK);
    }
    
    #[Route('/{id}', name: 'api_employee_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'Remove employee',
        content: null
    )]
    public function delete(string $id, DeleteEmployeeCommandHandler $handler, GetEmployeeCommandHandler $getEmployeeHandler): JsonResponse
    {
        $command = new GetEmployeeCommand($id);
        $employee = $getEmployeeHandler($command);
        if ($employee === null) {
            return new JsonResponse(json_encode(["message" => "Employee doesn't exist"], JSON_PRETTY_PRINT),
                Response::HTTP_NOT_FOUND
            );
        }
        
        $handler($employee);
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}


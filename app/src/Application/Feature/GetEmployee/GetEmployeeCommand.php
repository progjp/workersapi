<?php

namespace App\Application\Feature\GetEmployee;

readonly class GetEmployeeCommand
{
    public function __construct(public string $id
    ) {
    }
}

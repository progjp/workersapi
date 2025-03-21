<?php

declare(strict_types=1);

namespace App\Util\Exception;

class ExceptionDetail
{
    public function __construct(
        protected string  $issue,
        protected ?string $code = null
    )
    {
    }
    
    public function getIssue(): string
    {
        return $this->issue;
    }
    
    public function getCode(): ?string
    {
        return $this->code;
    }
}

<?php

namespace App\Services;

use RuntimeException;

class LoanRuleViolationException extends RuntimeException
{
    /**
     * @var array<string, mixed>
     */
    private array $details;

    /**
     * @param array<string, mixed> $details
     */
    public function __construct(string $message, array $details = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    /**
     * @return array<string, mixed>
     */
    public function details(): array
    {
        return $this->details;
    }
}


<?php

declare(strict_types=1);

namespace Unctom\EmailShield\Support;

class ValidationContext
{
    public readonly string $domain;

    public ?string $failedRule = null;

    public ?string $errorMessage = null;

    /**
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        public readonly string $email,
        public readonly array $options = []
    ) {
        $parts = explode('@', $this->email);
        $this->domain = $parts[1] ?? '';
    }

    public function fail(string $rule, string $message): void
    {
        $this->failedRule = $rule;
        $this->errorMessage = $message;
    }

    public function hasFailed(): bool
    {
        return $this->failedRule !== null;
    }
}

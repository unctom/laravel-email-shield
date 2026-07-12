<?php

declare(strict_types=1);

namespace Unctom\EmailShield\Support;

readonly class ValidationResult
{
    public function __construct(
        private bool $isValid,
        private ?string $failedRule = null,
        private ?string $errorMessage = null
    ) {}

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getFailedRule(): ?string
    {
        return $this->failedRule;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}

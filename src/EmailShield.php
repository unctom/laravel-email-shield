<?php

declare(strict_types=1);

namespace Unctom\EmailShield;

use Illuminate\Pipeline\Pipeline;
use Unctom\EmailShield\Pipes\CheckDisposable;
use Unctom\EmailShield\Pipes\CheckMxRecords;
use Unctom\EmailShield\Pipes\CheckRoleBased;
use Unctom\EmailShield\Pipes\CheckSyntax;
use Unctom\EmailShield\Support\ValidationContext;
use Unctom\EmailShield\Support\ValidationResult;

class EmailShield
{
    /**
     * @param  array<string, mixed>  $options
     */
    public function validate(string $email, array $options = []): ValidationResult
    {
        $context = new ValidationContext($email, $options);

        $pipes = [
            CheckSyntax::class,
        ];

        if (($options['check_disposable'] ?? true) === true) {
            $pipes[] = CheckDisposable::class;
        }

        if (($options['prevent_role_based'] ?? false) === true) {
            $pipes[] = CheckRoleBased::class;
        }

        if (($options['require_mx'] ?? true) === true) {
            $pipes[] = CheckMxRecords::class;
        }

        /** @var ValidationContext $context */
        $context = app(Pipeline::class)
            ->send($context)
            ->through($pipes)
            ->thenReturn();

        return new ValidationResult(
            ! $context->hasFailed(),
            $context->failedRule,
            $context->errorMessage
        );
    }
}

<?php

declare(strict_types=1);

namespace Unctom\EmailShield\Pipes;

use Closure;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Unctom\EmailShield\Support\ValidationContext;

class CheckSyntax
{
    public function handle(ValidationContext $context, Closure $next): mixed
    {
        $validator = new EmailValidator;
        if (! $validator->isValid($context->email, new RFCValidation)) {
            $context->fail(self::class, 'The email address format is invalid.');

            return $context;
        }

        return $next($context);
    }
}

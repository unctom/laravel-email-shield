<?php

declare(strict_types=1);

namespace Unctom\EmailShield\Pipes;

use Closure;
use Unctom\EmailShield\Support\ValidationContext;

class CheckRoleBased
{
    private const ROLE_BASED_PREFIXES = [
        'admin',
        'support',
        'info',
        'billing',
        'sales',
        'root',
        'contact',
    ];

    public function handle(ValidationContext $context, Closure $next): mixed
    {
        if (($context->options['prevent_role_based'] ?? false) === true) {
            $parts = explode('@', strtolower($context->email));
            $localPart = $parts[0];

            if (in_array($localPart, self::ROLE_BASED_PREFIXES, true)) {
                $context->fail(self::class, 'role based email addresses are not allowed.');

                return $context;
            }
        }

        return $next($context);
    }
}
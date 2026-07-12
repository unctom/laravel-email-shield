<?php

declare(strict_types=1);

namespace Unctom\EmailShield\Pipes;

use Closure;
use Unctom\EmailShield\Support\ValidationContext;

class CheckDisposable
{
    public function handle(ValidationContext $context, Closure $next): mixed
    {
        $domains = require __DIR__.'/../../resources/disposable-domains.php';

        if (in_array(strtolower($context->domain), $domains, true)) {
            $context->fail(self::class, 'Disposable email addresses are not allowed.');

            return $context;
        }

        return $next($context);
    }
}

<?php

declare(strict_types=1);

namespace Unctom\EmailShield\Pipes;

use Closure;
use Illuminate\Support\Facades\Cache;
use Unctom\EmailShield\Support\ValidationContext;

class CheckMxRecords
{
    public function handle(ValidationContext $context, Closure $next): mixed
    {
        $domain = $context->domain;

        if ($domain === '') {
            $context->fail(self::class, 'The email address domain is missing.');

            return $context;
        }

        /** @var bool $isValid */
        $isValid = Cache::remember("email-shield-mx:{$domain}", 86400, function () use ($domain) {
            return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
        });

        if (!$isValid) {
            $context->fail(self::class, 'The email address is invalid or not active');

            return $context;
        }

        return $next($context);
    }
}
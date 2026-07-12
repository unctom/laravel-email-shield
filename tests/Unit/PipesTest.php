<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Unctom\EmailShield\Pipes\CheckDisposable;
use Unctom\EmailShield\Pipes\CheckMxRecords;
use Unctom\EmailShield\Pipes\CheckRoleBased;
use Unctom\EmailShield\Pipes\CheckSyntax;
use Unctom\EmailShield\Support\ValidationContext;

it('fails syntax check on invalid email', function () {
    $context = new ValidationContext('invalid-email');
    $pipe = new CheckSyntax;

    $resultContext = $pipe->handle($context, fn ($c) => $c);

    expect($resultContext->hasFailed())->toBeTrue()
        ->and($resultContext->failedRule)->toBe(CheckSyntax::class);
});

it('passes syntax check on valid email', function () {
    $context = new ValidationContext('developer@gmail.com');
    $pipe = new CheckSyntax;

    $resultContext = $pipe->handle($context, fn ($c) => $c);

    expect($resultContext->hasFailed())->toBeFalse();
});

it('fails disposable check on disposable domain', function () {
    $context = new ValidationContext('test@mailinator.com');
    $pipe = new CheckDisposable;

    $resultContext = $pipe->handle($context, fn ($c) => $c);

    expect($resultContext->hasFailed())->toBeTrue()
        ->and($resultContext->failedRule)->toBe(CheckDisposable::class);
});

it('passes disposable check on clean domain', function () {
    $context = new ValidationContext('developer@gmail.com');
    $pipe = new CheckDisposable;

    $resultContext = $pipe->handle($context, fn ($c) => $c);

    expect($resultContext->hasFailed())->toBeFalse();
});

it('fails role-based check when option is enabled', function () {
    $context = new ValidationContext('admin@company.com', ['prevent_role_based' => true]);
    $pipe = new CheckRoleBased;

    $resultContext = $pipe->handle($context, fn ($c) => $c);

    expect($resultContext->hasFailed())->toBeTrue()
        ->and($resultContext->failedRule)->toBe(CheckRoleBased::class);
});

it('passes role-based check when option is disabled', function () {
    $context = new ValidationContext('admin@company.com');
    $pipe = new CheckRoleBased;

    $resultContext = $pipe->handle($context, fn ($c) => $c);

    expect($resultContext->hasFailed())->toBeFalse();
});

it('fails mx check on invalid domain', function () {
    Cache::shouldReceive('remember')
        ->once()
        ->with('email-shield-mx:invalid.test', Mockery::type('int'), Mockery::type('Closure'))
        ->andReturn(false);

    $context = new ValidationContext('test@invalid.test');
    $pipe = new CheckMxRecords;

    $resultContext = $pipe->handle($context, fn ($c) => $c);

    expect($resultContext->hasFailed())->toBeTrue()
        ->and($resultContext->failedRule)->toBe(CheckMxRecords::class);
});

it('passes mx check on valid domain and caches the result', function () {
    Cache::shouldReceive('remember')
        ->once()
        ->with('email-shield-mx:gmail.com', Mockery::type('int'), Mockery::type('Closure'))
        ->andReturn(true);

    $context = new ValidationContext('developer@gmail.com');
    $pipe = new CheckMxRecords;

    $resultContext = $pipe->handle($context, fn ($c) => $c);

    expect($resultContext->hasFailed())->toBeFalse();
});

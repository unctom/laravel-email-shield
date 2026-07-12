<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Unctom\EmailShield\EmailShield;
use Unctom\EmailShield\Pipes\CheckDisposable;
use Unctom\EmailShield\Pipes\CheckSyntax;
use Unctom\EmailShield\Support\ValidationResult;

it('validates a correct email successfully', function () {
    Cache::shouldReceive('remember')->once()->andReturn(true);

    $shield = new EmailShield;
    $result = $shield->validate('developer@gmail.com');

    expect($result)->toBeInstanceOf(ValidationResult::class)
        ->and($result->isValid())->toBeTrue();
});

it('fails validation for a disposable email by default', function () {
    $shield = new EmailShield;
    $result = $shield->validate('test@mailinator.com');

    expect($result->isValid())->toBeFalse()
        ->and($result->getFailedRule())->toBe(CheckDisposable::class);
});

it('passes disposable email if disposable check is disabled', function () {
    Cache::shouldReceive('remember')->once()->andReturn(true);

    $shield = new EmailShield;
    $result = $shield->validate('test@mailinator.com', ['check_disposable' => false]);

    expect($result->isValid())->toBeTrue();
});

it('fails validation on syntax immediately', function () {
    $shield = new EmailShield;
    $result = $shield->validate('invalid-email-format');

    expect($result->isValid())->toBeFalse()
        ->and($result->getFailedRule())->toBe(CheckSyntax::class);
});

<?php

declare(strict_types=1);

use Unctom\EmailShield\Support\ValidationContext;
use Unctom\EmailShield\Support\ValidationResult;

it('can initialize a validation context', function () {
    $context = new ValidationContext('developer@gmail.com', ['check_disposable' => true]);

    expect($context->email)->toBe('developer@gmail.com')
        ->and($context->domain)->toBe('gmail.com')
        ->and($context->options)->toBe(['check_disposable' => true])
        ->and($context->hasFailed())->toBeFalse();
});

it('can mark a validation context as failed', function () {
    $context = new ValidationContext('developer@gmail.com');
    $context->fail('CheckDisposable', 'The email domain is disposable.');

    expect($context->hasFailed())->toBeTrue()
        ->and($context->failedRule)->toBe('CheckDisposable')
        ->and($context->errorMessage)->toBe('The email domain is disposable.');
});

it('can initialize a validation result', function () {
    $result = new ValidationResult(true);

    expect($result->isValid())->toBeTrue()
        ->and($result->getFailedRule())->toBeNull()
        ->and($result->getErrorMessage())->toBeNull();
});

it('can initialize a failed validation result', function () {
    $result = new ValidationResult(false, 'CheckDisposable', 'The email domain is disposable.');

    expect($result->isValid())->toBeFalse()
        ->and($result->getFailedRule())->toBe('CheckDisposable')
        ->and($result->getErrorMessage())->toBe('The email domain is disposable.');
});

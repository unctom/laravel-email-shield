<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Unctom\EmailShield\Rules\EmailAuthentic;

it('passes a valid email address', function () {
    Cache::shouldReceive('remember')->once()->andReturn(true);

    $rule = new EmailAuthentic;
    $failed = false;

    $rule->validate('email', 'developer@gmail.com', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('fails a disposable email address with error message', function () {
    $rule = (new EmailAuthentic)->notDisposable();

    $failed = false;
    $errorMessage = null;

    $rule->validate('email', 'test@mailinator.com', function (string $message) use (&$failed, &$errorMessage) {
        $failed = true;
        $errorMessage = $message;
    });

    expect($failed)->toBeTrue()
        ->and($errorMessage)->toBe('Disposable email addresses are not allowed.');
});

it('fails a role-based email when preventRoleBased is chained', function () {
    $rule = (new EmailAuthentic)->preventRoleBased();

    $failed = false;

    $rule->validate('email', 'admin@company.com', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

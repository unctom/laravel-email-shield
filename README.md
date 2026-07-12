# Laravel Email Shield

[![Latest Version on Packagist](https://img.shields.io/packagist/v/unctom/laravel-email-shield.svg?style=flat-square)](https://packagist.org/packages/unctom/laravel-email-shield)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/unctom/laravel-email-shield/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/unctom/laravel-email-shield/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/unctom/laravel-email-shield.svg?style=flat-square)](https://packagist.org/packages/unctom/laravel-email-shield)

Laravel Email Shield is a highly extensible, framework-native email authenticity and validation engine for Laravel.

It protects your application from junk sign-ups, disposable/throwaway addresses, role-based accounts, and dead domains by executing a high-performance validation pipeline that fails fast on simple syntax checks before ever executing slow network DNS lookups. Furthermore, it intelligently caches DNS checks to prevent throttling and rate limits during high traffic.

## Installation

You can install the package via composer:

```bash
composer require unctom/laravel-email-shield
```

## Usage

### Using the Validation Rule

The most common way to use Laravel Email Shield is via the provided `EmailAuthentic` validation rule during form requests.

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Unctom\EmailShield\Rules\EmailAuthentic;

Route::post('/register', function (Request $request) {
    $request->validate([
        'email' => [
            'required',
            'string',
            'email',
            (new EmailAuthentic())
                ->notDisposable()     // Blocks mailinator, tempmail, etc.
                ->preventRoleBased()  // Blocks admin@, support@, info@, etc.
                ->requireMx(),        // Ensures the domain has valid MX or A records
        ],
    ]);

    // ...
});
```

### Using the Facade

If you need to validate an email address outside of Laravel's validation system, you can use the `EmailShield` facade directly:

```php
use Unctom\EmailShield\Facades\EmailShield;

$result = EmailShield::validate('developer@example.com', [
    'check_disposable' => true,
    'prevent_role_based' => true,
    'require_mx' => true,
]);

if (! $result->isValid()) {
    echo "Validation failed: " . $result->getErrorMessage();
    echo "Failed at step: " . $result->getFailedRule();
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Thomas B.](https://github.com/unctom)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

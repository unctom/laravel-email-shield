<?php

declare(strict_types=1);

namespace Unctom\EmailShield\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Unctom\EmailShield\Facades\EmailShield;

class EmailAuthentic implements ValidationRule
{
    /**
     * @var array<string, bool>
     */
    private array $options = [];

    public function notDisposable(): self
    {
        $this->options['check_disposable'] = true;

        return $this;
    }

    public function preventRoleBased(): self
    {
        $this->options['prevent_role_based'] = true;

        return $this;
    }

    public function requireMx(): self
    {
        $this->options['require_mx'] = true;

        return $this;
    }

    /**
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a valid email address.');

            return;
        }

        $result = EmailShield::validate($value, $this->options);

        if (! $result->isValid()) {
            $errorMessage = $result->getErrorMessage();

            if ($errorMessage !== null) {
                $fail($errorMessage);
            } else {
                $fail('The :attribute is invalid.');
            }
        }
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class RottenTomatoesMovie implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Must be a valid URL
        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('The :attribute must be a valid URL.');

            return;
        }

        $parsed = parse_url($value);

        $host = $parsed['host'] ?? null;
        $path = $parsed['path'] ?? '';

        // Must belong to rottentomatoes.com (allowing subdomains)
        if (! $host || ! str_ends_with($host, 'rottentomatoes.com')) {
            $fail('The :attribute must be a URL within the rottentomatoes.com domain.');

            return;
        }

        // Must start with /m/ (movie pages)
        if (! preg_match('#^/m/[^/]+#', $path)) {
            $fail('The :attribute must be a Rotten Tomatoes movie page (starting with /m/).');
        }
    }
}

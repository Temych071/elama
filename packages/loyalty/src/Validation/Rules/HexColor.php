<?php

declare(strict_types=1);

namespace Loyalty\Validation\Rules;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Rule;

final class HexColor implements Rule
{
    private const MESSAGE_TRANS_KEY = 'validation.hex-color';
    private const MESSAGE_FALLBACK_TEXT = 'Specified color is invalid.';

    public function passes($attribute, $value): bool
    {
        return (
            is_string($value)
            && preg_match('/^#([0-9a-f]{6}|[0-9a-f]{8})$/i', $value) === 1
        );
    }

    public function message(): array|string|Translator|Application|null
    {
        $message = trans(self::MESSAGE_TRANS_KEY);

        return $message === self::MESSAGE_TRANS_KEY
            ? [self::MESSAGE_FALLBACK_TEXT]
            : $message;
    }
}

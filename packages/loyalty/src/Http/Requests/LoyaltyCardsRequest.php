<?php

declare(strict_types=1);

namespace Loyalty\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Loyalty\Enums\LoyaltyClientGender;
use Loyalty\Models\Loyalty;

final class LoyaltyCardsRequest extends FormRequest
{
    protected array $fields = [
        'name',
        'surname',
        'email',
        'birthday',
        'gender',
    ];

    public function rules(): array
    {
        /** @var Loyalty $loyalty */
        $loyalty = $this->route('apiLoyalty');
        $rules = [
            'cards' => ['required', 'array'],
            'cards.*' => ['array'],
            'cards.*.card_number' => ['required', 'string', 'max:255'],
            'cards.*.phone' => ['nullable', 'string', 'max:32'],

            'cards.*.name' => ['exclude_without:cards.*.phone', 'nullable', 'string', 'max:255'],
            'cards.*.surname' => ['exclude_without:cards.*.phone', 'nullable', 'string', 'max:255'],
            'cards.*.email' => ['exclude_without:cards.*.phone', 'nullable', 'string', 'max:255', 'email'],
            'cards.*.gender' => ['exclude_without:cards.*.phone', 'nullable', new Enum(LoyaltyClientGender::class)],
            'cards.*.birthday' => ['exclude_without:cards.*.phone', 'nullable', 'date'],

            'cards.*.sms_notifications' => ['exclude_without:cards.*.phone', 'boolean'],
            'cards.*.email_notifications' => ['exclude_without:cards.*.phone', 'boolean'],
            'cards.*.terms_accepted' => ['exclude_without:cards.*.phone', 'boolean'],
        ];

        foreach ($loyalty->form_settings->custom_fields as $customField) {
            $rules['cards.*.' . $customField->key] = ['exclude_without:cards.*.phone', 'nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    public function getCardsData(): array
    {
        return $this->validated('cards');
    }
}

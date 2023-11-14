<?php

namespace Loyalty\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Loyalty\Enums\LoyaltyClientGender;
use Loyalty\Models\Loyalty;

class LoyaltyFormRequest extends FormRequest
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
        $loyalty = $this->route('publicLoyalty');
        $formSettings = $loyalty->form_settings;

        $rules = [
            'name' => ['string', 'max:255'],
            'surname' => ['string', 'max:255'],
            'email' => ['string', 'max:255', 'email'],
            'gender' => ['string', new Enum(LoyaltyClientGender::class)],
            'birthday' => ['date'],
        ];

        foreach ($this->fields as $field) {
            $fieldSettings = $field . '_field';
            $rules[$field][] = $formSettings->$fieldSettings->required ? 'required' : 'nullable';
        }

        foreach ($formSettings->custom_fields as $customField) {
            $rules['custom_fields.' . $customField->key] = [
                $customField->required ? 'required' : 'nullable',
                'string', 'max:255',
            ];
        }

        if ($formSettings->sms_required) {
            $rules['sms_notifications'] = ['required', 'boolean', 'accepted'];
        }

        if ($formSettings->email_required) {
            $rules['email_notifications'] = ['required', 'boolean', 'accepted'];
        }

        if ($formSettings->terms_required) {
            $rules['terms_accepted'] = ['required', 'boolean', 'accepted'];
        }

//        dd($rules);

        return $rules;
    }
}

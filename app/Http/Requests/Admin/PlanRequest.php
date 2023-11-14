<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Module\Billing\Subscription\Enums\PlanFeature;
use Module\Billing\Subscription\Enums\PlanStatus;

final class PlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'formatted_price' => ['required', 'numeric', 'min:1'],
            'status' => ['required', 'string', new Enum(PlanStatus::class)],
            'visits_limit' => ['nullable', 'numeric', 'min:0'],
            'review_forms_limit' => ['nullable', 'numeric', 'min:0'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', new Enum(PlanFeature::class)],
        ];
    }
}

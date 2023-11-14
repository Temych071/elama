<?php

declare(strict_types=1);

namespace Reviews\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Reviews\Enums\ReviewFormType;
use Reviews\Models\ReviewForm;

final class ReviewRequest extends FormRequest
{
    /**
     * @return array{comment: string[], stars: string[], policy: string[], name?: string[], contact?: string[]}
     */
    public function rules(): array
    {
        /** @var ReviewForm $reviewForm */
        $reviewForm = ReviewForm::query()
            ->where('slug', $this->route('slug'))
            ->firstOrFail();

        $withoutComment = (
            $reviewForm->type === ReviewFormType::SIMPLE
            && $this->input('stars') >= $reviewForm->min_stars_for_publish
        );

        $commentRequired = $withoutComment
            ? 'nullable'
            : 'required';

        $rules = [
            'comment' => [$commentRequired, 'string', 'max:1000'],
            'stars' => ['required', 'numeric', 'integer', 'min:1', 'max:5'],
            'policy' => ['required', 'accepted'],
        ];

        if (!$withoutComment) {
            $fields = $reviewForm->page_settings['fields'] ?? [];

            if ($fields['name']['show'] ?? false) {
                $rules['name'] = [$fields['name']['required'] ?? false ? 'required' : 'nullable', 'string', 'max:255'];
            }

            if ($fields['contact']['show'] ?? false) {
                $rules['contact'] = [$fields['contact']['required'] ?? false ? 'required' : 'nullable', 'string', 'max:255'];
            }
        }

        return $rules;
    }
}

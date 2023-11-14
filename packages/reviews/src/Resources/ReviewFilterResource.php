<?php

namespace Reviews\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Reviews\Enums\AnswerFilter;

class ReviewFilterResource extends JsonResource
{
    /**
     * @return array{rating: array{label: string, name: string, options: array{title: string, value: string}[]}, branches: array{label: string, name: string, options: array<int|string, mixed>&mixed[]}, comments: array{label: string, name: string, options: array{title: string, value: null}[]|array{title: string, value: string}[]}}
     */
    public function toArray($request): array
    {
        return [
            'rating' => [
                'label' => 'Рейтинг',
                'name' => 'rating',
                'options' => [
                    ['title' => 'По новизне', 'value' => 'new'],
                    ['title' => 'Сначала плохие отзывы', 'value' => 'bad_reviews'],
                    ['title' => 'Сначала хорошие отзывы', 'value' => 'good_reviews'],
                ],
            ],
            'branches' => [
                'label' => 'Филиалы',
                'name' => 'branches',
                'options' => [
                    ['title' => 'Все', 'value' => null],
                    ...$this->reviewForms()->get(['id', 'name'])->map(fn ($reviewForm): array => [
                        'title' => $reviewForm->name,
                        'value' => $reviewForm->id,
                    ])
                ],
            ],
//            'comments' => [
//                'label' => 'Комментарии менеджера',
//                'name' => 'comments',
//                'options' => [
//                    ['title' => 'Все', 'value' => null],
//                    ['title' => 'С комментариями', 'value' => 'has_comments'],
//                    ['title' => 'Без комментариев', 'value' => 'no_comments'],
//                ],
//            ],
            'answer' => [
                'label' => 'Ответы',
                'name' => 'answer',
                'options' => [
                    ['title' => 'Все', 'value' => null],
                    ['title' => 'С ответом', 'value' => AnswerFilter::has_answer->value],
                    ['title' => 'Без ответа', 'value' => AnswerFilter::no_answer->value],
                ],
            ],
        ];
    }
}

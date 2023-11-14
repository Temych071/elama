<?php

declare(strict_types=1);

namespace Reviews\Actions;

use Illuminate\Support\Str;
use Reviews\Enums\ReviewFormType;
use Reviews\Models\ReviewForm;
use Module\Campaign\Models\Campaign;

final class CreateReviewFormAction
{
    public function execute(Campaign $project, string $name): ReviewForm
    {
        /** @var ReviewForm $reviewForm */
        $reviewForm = $project->reviewForms()->make([
            'name' => $name,
            'slug' => Str::before(Str::uuid(), '-'),
            'type' => ReviewFormType::SIMPLE,
            'min_stars_for_publish' => 5,
            'max_stars_for_notification' => 4,

            'phrases' => [
                'text_title' => 'Название компании',
                'text_before_contact_field' => 'Хотите, чтобы с Вами связались?',
                'text_address' => 'Москва, Пресненская набережная, 10',
                'text_form_title' => 'Оставьте отзыв о нашей работе',
                'text_aggregators_title' => 'Спасибо за вашу высокую оценку',
                'text_aggregators_desc' => 'Будем благодарны, если вы оставьте отзыв на одной из платформ ниже',
            ],

            'page_settings' => [
                'fields' => [
                    'name' => [
                        'show' => true,
                        'required' => false,
                    ],
                    'contact' => [
                        'show' => true,
                        'required' => false,
                    ],
                ],
                'colors' => [
                    'background' => '#F8F8F8',
                    'buttons' => '#FFCF00',
                ],
                'policy' => false,
                'policyLink' => '',
                'show_reviews_list' => false,
            ],

            'messengers' => [
                [
                    'title' => 'WhatsApp',
                    'link' => 'https://web.whatsapp.com/',
                ],
                [
                    'title' => 'Telegram',
                    'link' => 'https://t.me/',
                ],
            ],
            'external_aggregators' => [
                [
                    'title' => 'Яндекс.Картах',
                    'link' => 'https://maps.yandex.ru/',
                ],
                [
                    'title' => '2ГИС',
                    'link' => 'https://2gis.ru/',
                ],
            ],
        ]);
        $reviewForm->logo_path = ReviewForm::DEFAULT_MEDIA_VALUE;
        $reviewForm->save();

        return $reviewForm;
    }
}

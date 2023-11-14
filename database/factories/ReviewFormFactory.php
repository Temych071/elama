<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Module\Campaign\Models\Campaign;
use Reviews\Models\ReviewForm;

class ReviewFormFactory extends Factory
{
    protected $model = ReviewForm::class;

    public function definition(): array
    {
        $name = fake()->name();
        return [
            'slug' => Str::before(Str::uuid(), '-'),
            'project_id' => Campaign::query()->inRandomOrder()->firstOrCreate()->id,
            'name' => $name,
            'min_stars_for_publish' => fake()->numberBetween(1, 5),
            'max_stars_for_notification' => fake()->numberBetween(1, 5),
            'max_stars_for_messengers' => fake()->numberBetween(1, 5),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),


            'phrases' => [
                'text_title' => $name,
            ],

            'page_settings' => [
                'fields' => [
                    'name' => [
                        'show' => true,
                        'required' => true,
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
            ],

            'messengers' => [
                [
                    'title' => 'Telegram',
                    'link' => 'https://t.me/',
                ],
                [
                    'title' => 'ВКонтакте',
                    'link' => 'https://vk.com/',
                ],
            ],
            'external_aggregators' => [
                [
                    'title' => 'Яндекс.Карты',
                    'link' => 'https://maps.yandex.ru/',
                ],
                [
                    'title' => '2ГИС',
                    'link' => 'https://2gis.ru/',
                ],
            ],
        ];
    }
}

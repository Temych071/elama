<?php

declare(strict_types=1);

namespace Loyalty\DTO;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class LoyaltyFormSettings extends Data
{
    /**
     * @param  string  $company_name
     * @param  string  $company_desc
     * @param  string  $header_color
     * @param  string  $button_color
     * @param  string  $title
     * @param  string  $button_text
     * @param  LoyaltyFormField  $name_field
     * @param  LoyaltyFormField  $surname_field
     * @param  LoyaltyFormField  $email_field
     * @param  LoyaltyFormField  $birthday_field
     * @param  LoyaltyFormField  $gender_field
     * @param  bool  $terms_required
     * @param  bool  $sms_required
     * @param  bool  $email_required
     * @param  string|null  $custom_terms
     * @param  DataCollection<LoyaltyFormCustomField>  $custom_fields
     */
    public function __construct(
        public string $company_name = 'Компания',
        public string $company_desc = 'Описание компании',
        public string $header_color = '#F6F8FA',
        public string $button_color = '#1975FF',

        public string $title = 'Получите карту Лояльности',
        public string $button_text = 'Отправить',

        public LoyaltyFormField $name_field = new LoyaltyFormField('Имя', true),
        public LoyaltyFormField $surname_field = new LoyaltyFormField('Фамилия', true),
        public LoyaltyFormField $email_field = new LoyaltyFormField('E-Mail', true),
        public LoyaltyFormField $birthday_field = new LoyaltyFormField('День рождения', true),
        public LoyaltyFormField $gender_field = new LoyaltyFormField('Пол', true),

        public bool $terms_required = true,
        public bool $sms_required = true,
        public bool $email_required = true,

        public ?string $custom_terms = null,

        #[DataCollectionOf(LoyaltyFormCustomField::class)]
        public DataCollection $custom_fields = new DataCollection(LoyaltyFormCustomField::class, []),
    ) {
    }
}

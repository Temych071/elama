<?php

declare(strict_types=1);

namespace SocialWidget\Crm;

final class CreateLeadCommand
{
    public function __construct(
        public readonly string $title,
//        public readonly string $name,
        public readonly ?string $utmSource = null,
        public readonly ?string $utmMedium = null,
        public readonly ?string $utmCampaign = null,

        /**
         * @var array<string, string>
         */
        public readonly array $phones = [],

        /**
         * @var array<string, string>
         */
        public readonly array $emails = [],

        /**
         * @var array<string, string|array>
         */
        public readonly array $fields = [],

        // only for amoCrm
        public readonly ?string $referer = null,
        public readonly ?string $form_page = null,
        public readonly ?string $ip = null,
    ) {
    }
}

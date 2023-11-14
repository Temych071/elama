<?php

declare(strict_types=1);

namespace Reviews\Parsers\Yandex\DTO;

final class YandexCompany
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $logo_url = null,
    ) {
    }
}

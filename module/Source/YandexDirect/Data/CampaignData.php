<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Data;

use Spatie\LaravelData\Data;

final class CampaignData extends Data
{
    public function __construct(
        public int $Id,
        public string $Name,
        public bool $IsFromReport = false,
    ) {
    }
}

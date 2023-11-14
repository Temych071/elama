<?php

declare(strict_types=1);

namespace Module\Campaign\DTO;

use Spatie\LaravelData\Data;

final class ProjectSettingsChecks extends Data
{
    public function __construct(
        public bool $showSeoAudit = true,
        public bool $showLinkChecker = true,
    ) {
    }

}

<?php

declare(strict_types=1);

namespace Module\Source\Sources\Events;

use Module\Source\Sources\Models\Source;

final class SourceUpdateFinishedEvent
{
    public function __construct(
        public Source $source,
    ) {
    }
}

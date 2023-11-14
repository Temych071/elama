<?php

declare(strict_types=1);

namespace Module\Campaign\Events;

use Illuminate\Queue\SerializesModels;
use Module\Campaign\Models\Campaign;

final class CreateFirstProjectEvent
{
    use SerializesModels;

    public function __construct(public Campaign $campaign)
    {
    }
}

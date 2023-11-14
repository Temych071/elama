<?php

declare(strict_types=1);

namespace Module\LinkChecker\Dto;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class LinkCheckItemDto extends Data
{
    public function __construct(
        public readonly string $url,

        /** @var DataCollection<int, LinkCheckAdDto> */
        #[DataCollectionOf(LinkCheckAdDto::class)]
        public readonly DataCollection $ads,
    ) {
    }
}

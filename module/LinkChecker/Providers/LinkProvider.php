<?php

namespace Module\LinkChecker\Providers;

use Module\LinkChecker\Actions\FilterLinkAction;
use Module\LinkChecker\Dto\LinkCheckItemDto;

abstract class LinkProvider
{
    /** @return LinkCheckItemDto[] */
    abstract public function getLinkList($sourceId): iterable;

    protected function filterLink(string $url): string
    {
        return app(FilterLinkAction::class)->execute($url);
    }
}

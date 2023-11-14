<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Module\LinkChecker\LinkChecker;
use Module\LinkChecker\Providers\DirectLinkProvider;
use Module\LinkChecker\Providers\VkLinkProvider;
use Module\Source\Sources\Models\Source;

class StartLinkCheckerCommand extends Command
{
    protected $signature = 'source:link-checker {source? : The id of the source}';

    protected $description = 'Start link checks';

    public function handle(LinkChecker $linkChecker): void
    {
        $providers = [
            Source::TYPE_VK => new VkLinkProvider(),
            Source::TYPE_YANDEX_DIRECT => new DirectLinkProvider(),
        ];

        $sources = $this->getSources($providers)
            ->each(fn (Source $it) => $linkChecker->start($it->id, $providers[$it->settings_type]));

        $this->info('Sources dispatched: ' . $sources->count());
    }

    /**
     * @return array|Collection<int, Source>
     */
    private function getSources(array $providers): array|Collection
    {
        if ($sourceId = $this->argument('source')) {
            return Source::query()
                ->where('id', $sourceId)
                ->has('campaign')
                ->limit(1)
                ->get();
        }

        return Source::query()
            ->whereIn('settings_type', array_keys($providers))
            ->whereNotNull('settings_id')
            ->has('campaign')
            ->get();
    }
}

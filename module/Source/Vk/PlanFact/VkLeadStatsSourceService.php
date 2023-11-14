<?php

declare(strict_types=1);

namespace Module\Source\Vk\PlanFact;

use App\Infrastructure\DateRange;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Module\PlanFact\Contracts\MetricsSourceService;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Models\VkSettings;

final class VkLeadStatsSourceService implements MetricsSourceService
{
    public function __construct(
        private readonly Source $source,
    ) {
    }
    /**
     * @inheritDoc
     */
    #[ArrayShape([['date' => Carbon::class, 'requests' => "int"]])]
    public function getConversions(
        DateRange $period,
        ?array $filters = null
    ): array {
        /** @var VkSettings $settings */
        $settings = $this->source->settings;

        $q = DB::table('vk_leads')
            ->selectRaw('COUNT(id) as requests, DATE(created_at) as date')
            ->where('source_id', $this->source->id)
            ->whereInDateRange($period, 'created_at')
            ->groupBy('date');

        if (!$settings->is_vk_lead_messages) {
            $q->whereNot('type', 'message_new');
        }

        if (!$settings->is_vk_lead_forms) {
            $q->whereNot('type', 'lead_forms_new');
        }


        return $q->get()
            ->map(static fn ($item): array => [
                'date' => $item->date,
                'requests' => $item->requests,
            ])
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getDomains(?DateRange $period = null, ?array $filters = null): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getDevices(?DateRange $period = null, ?array $filters = null): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getCampaignUtms(): array
    {
        return [];
    }

    public function getMediumUtms(): array
    {
        return [];
    }

    public function getSourceUtms(): array
    {
        return [];
    }
}

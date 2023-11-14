<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Actions\Settings;

use App\Infrastructure\DateRange;
use Illuminate\Support\Carbon;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Data\CampaignData;
use Module\Source\YandexDirect\Exceptions\YandexDirectReportNotReadyException;
use Module\Source\YandexDirect\Services\YandexDirectService;
use Spatie\LaravelData\DataCollection;

final class GetCampaignListAction
{
    /**
     * Объединяет результаты из API управления и отчетов
     * т.к. некоторые типы кампаний поддерживаются только в API отчетов
     *
     * @return DataCollection<int, CampaignData>
     * @throws YandexDirectReportNotReadyException
     */
    public function execute(Campaign $campaign): DataCollection
    {
        /** @var Source $source */
        $source = $campaign->yandexDirectSource;

        $service = new YandexDirectService($source->authToken);

        $campaigns = $service->getCampaigns();
        $reportCampaigns = $this->fetchFromReport($service, $source->id);

        foreach ($reportCampaigns as $item) {
            $exists = array_filter($campaigns, static fn ($it): bool => $it['Id'] === $item['Id']);

            if ($exists === []) {
                $campaigns[] = $item;
            }
        }

        return CampaignData::collection($campaigns);
    }

    /**
     * @throws YandexDirectReportNotReadyException
     */
    private function fetchFromReport(YandexDirectService $service, mixed $sourceId): array
    {
        $reportCampaigns = $service->reports(
            name: 'campaigns-list-' . $sourceId,
            type: 'CAMPAIGN_PERFORMANCE_REPORT',
            dateRange: new DateRange(Carbon::now()->subWeeks(2)),
            fields: [
                'CampaignId',
                'CampaignName',
            ],
            headers: [
                'skipReportHeader' => 'true',
                'skipReportSummary' => 'true',
                'processingMode' => 'online',
            ],
        );

        return array_map(static fn ($it): array => [
            'Id' => (int)$it['CampaignId'],
            'Name' => $it['CampaignName'],
            'IsFromReport' => true,
        ], $reportCampaigns);
    }
}

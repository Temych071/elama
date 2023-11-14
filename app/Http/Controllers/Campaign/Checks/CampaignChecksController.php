<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign\Checks;

use App\Exports\ChecksExport;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Module\Campaign\Actions\GetAuditPageDataAction;
use Module\Campaign\Models\Campaign;
use Module\LinkChecker\Models\SeoAdsAudit;
use Module\LinkChecker\Models\SeoAudit;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\Checks\GetCheckResultsAction as GetVkCheckResultsAction;
use Module\Source\YandexDirect\Checks\GetCheckResultsAction as GetYandexDirectCheckResultsAction;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class CampaignChecksController
{
    public const CACHE_KEY = 'checks:result:without-filters:';
    public const CACHE_TTL = 15 * 60;

    public function show(Request $request, Campaign $campaign, ?string $source = null): Responsable
    {
        if (!in_array($source, Source::CABINET_SOURCES, true)) {
            $source = null;
        }

        return Inertia::render('Campaign/Checks/Checks', [
            'campaign' => $campaign->only(['id']),
            'openSource' => $source,

            ...app(GetAuditPageDataAction::class)->execute($campaign),
        ]);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function exportSource(Request $request, Campaign $campaign, ?string $sourceName): ?StreamedResponse
    {
        $sourceCheck = [];
        $checks = $this->load($request, $campaign);

        if (!$sourceName) {
            return $this->makeExport($checks, $campaign);
        }
        if ($checks[$sourceName]) {
            $sourceCheck[$sourceName] = $checks[$sourceName];
            return $this->makeExport($sourceCheck, $campaign);
        }

        return null;
    }

    public function load(Request $request, Campaign $campaign): array
    {
        $filters = $this->getFilters($request);

        return is_null($filters)
            ? Cache::remember(
                key: self::CACHE_KEY . $campaign->id,
                ttl: self::CACHE_TTL,
                callback: fn (): array => $this->getResult($campaign),
            )
            : $this->getResult($campaign, $filters);
    }

    private function getResult(Campaign $campaign, array $filters = []): array
    {
        $checks = [];

        // Если есть источник и он настроен
        if ($campaign->yandexDirectSource?->settings) {
            $checks[Source::TYPE_YANDEX_DIRECT] = app(GetYandexDirectCheckResultsAction::class)
                ->execute($campaign->yandexDirectSource, $filters[Source::TYPE_YANDEX_DIRECT] ?? []);
        }

        if ($campaign->vkSource?->settings) {
            $checks[Source::TYPE_VK] = app(GetVkCheckResultsAction::class)
                ->execute($campaign->vkSource, $filters[Source::TYPE_VK] ?? []);
        }

        return $checks;
    }

    private function getFilters(Request $request): ?array
    {
        $filters = $request->toArray()['filters'] ?? null;
        if (is_null($filters)) {
            return null;
        }

        // Если фильтры для всех кабинетов пустые - возвращать null
        // Чтобы кэш срабатывал)
        foreach ($filters as $val) {
            if (!empty($val)) {
                foreach ($val as $filter) {
                    if (!empty($filter)) {
                        return $filters;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function makeExport(array $checks, Campaign $campaign): StreamedResponse
    {
        $export = new ChecksExport($checks);
        $spreadSheet = $export->create();

        $filename = $campaign->name.'-аудит-'.Carbon::now()->format('d.m.Y').'.xlsx';

        return response()->stream(function () use ($spreadSheet): void {
            (new Xlsx($spreadSheet))->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="'.$filename.'"',
        ])->send();
    }
}

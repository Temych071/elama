<?php

namespace Module\PlanFact\Services;

use App\Infrastructure\DateRange;
use Module\Campaign\Models\Campaign;
use Module\PlanFact\Actions\GetPlanFactDataAction;
use Module\PlanFact\Contracts\CabinetSourceService;
use Module\PlanFact\Contracts\EcommerceSourceService;
use Module\PlanFact\Contracts\MetricsSourceService;
use Module\PlanFact\DTO\PlanFactGoalData;
use Module\PlanFact\Models\PlanSettings;
use Module\Source\GoogleAnalytics\Models\AnalyticsSettings;
use Module\Source\GoogleAnalytics\PlanFact\AnalyticsMetricsSourceService;
use Module\Source\Sources\Models\Source;
use Module\Source\Vk\PlanFact\VkCabinetSourceService;
use Module\Source\Vk\PlanFact\VkLeadStatsSourceService;
use Module\Source\YandexDirect\PlanFact\YandexDirectCabinetSourceService;
use Module\Source\YandexMetrika\Models\MetrikaSourceSettings;
use Module\Source\YandexMetrika\PlanFact\MetrikaMetricsSourceService;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\InvalidDataCollectionModification;

class PlanFactBuilder
{
    /** @var CabinetSourceService[] */
    private array $cabinetServices = [];
    /** @var MetricsSourceService[] */
    private array $statsServices = [];
    /** @var EcommerceSourceService[] */
    private array $ecommerceServices = [];

    private array $filters = [
        'utm_campaign' => [],
        'utm_medium' => [],
        'utm_source' => [],
        'campaign_name' => [],
        'device' => null,
        'domain' => null,
        'goals' => [],
        'seo' => null,

        'cabinets' => [],
    ];

    private PlanSettings $plan;
    private DateRange $period;
    /**
     * @var DataCollection|\Module\PlanFact\DTO\PlanFactGoalData[]|null
     */
    private ?DataCollection $availableGoals = null;

    public function __construct(
        public readonly Campaign $campaign,
    ) {
        $this->loadMetricsServices();
    }

    public static function create(Campaign $campaign): self
    {
        return new self($campaign);
    }

    public function loadMetricsServices(): self
    {
        /** @var Source $source */
        $sources = $this->campaign->statsSources;

        foreach ($sources as $source) {
            $this->addMetricsSource($source);
        }

        return $this;
    }

    private function addMetricsSource(Source $source): void
    {
        $serviceClass = match ($source->settings_type) {
            Source::TYPE_YANDEX_METRIKA => MetrikaMetricsSourceService::class,
            Source::TYPE_GOOGLE_ANALYTICS => AnalyticsMetricsSourceService::class,
            Source::TYPE_VK => VkLeadStatsSourceService::class,

            default => null,
        };

        if (is_null($serviceClass)) {
            return;
        }

        $service = new $serviceClass($source);
        $this->statsServices[] = $service;

        if (in_array(EcommerceSourceService::class, class_implements($service), true)) {
            $this->ecommerceServices[] = $service;
        }

        if (
            $source->settings instanceof MetrikaSourceSettings
            || $source->settings instanceof AnalyticsSettings
        ) {
            $this->availableGoals = PlanFactGoalData::collection(
                array_map(static fn ($goal): array => [
                    'id' => $goal['id'],
                    'name' => $goal['name'],
                ], $source->settings->goals->toArray())
            );
        }
    }

    public function setPeriod(DateRange $value): self
    {
        $this->period = $value;

        return $this;
    }

    /**
     * @return DataCollection|PlanFactGoalData[]
     */
    public function getAvailableGoals(): DataCollection
    {
        return $this->availableGoals ?? new DataCollection(PlanFactGoalData::class, []);
    }

    public function setPlan(PlanSettings $plan): self
    {
        $this->plan = $plan;

        $this->addCabinets($this->plan->sources ?? [])
            ->setFilterSeo($this->plan->seo)
            ->setFilterDevice($this->plan->device)
            ->setFilterGoals($this->plan->goals)
            ->setFilterDomain($this->plan->domain)
            ->setFilterSourceUtm($this->plan->utm_source)
            ->setFilterMediumUtm($this->plan->utm_medium)
            ->setFilterCampaignUtm($this->plan->utm_campaign)
            ->setFilterCampaignName($this->plan->campaign_name);

        return $this;
    }

    public function setFilterSeo(array|bool|null $value = null): self
    {
        if (is_null($value)) {
            $this->filters['seo'] = null;
        } elseif (is_array($value)) {
            $this->filters['seo'] = $value;
        } elseif (is_bool($value)) {
            $this->filters['seo']['enabled'] = $value;
        }

        return $this;
    }

    public function setFilterSourceUtm(array|string|null $value = null): self
    {
        $this->filters['utm_source'] = is_null($value) ? null : (array)$value;
        return $this;
    }

    public function setFilterMediumUtm(array|string|null $value = null): self
    {
        $this->filters['utm_medium'] = is_null($value) ? null : (array)$value;
        return $this;
    }

    public function setFilterCampaignUtm(array|string|null $value = null): self
    {
        $this->filters['utm_campaign'] = is_null($value) ? null : (array)$value;
        return $this;
    }

    public function setFilterCampaignName(array|string|null $value = null): self
    {
        $this->filters['campaign_name'] = is_null($value) ? null : (array)$value;
        return $this;
    }

    public function setFilterDevice(?string $value): self
    {
        $this->filters['device'] = $value;
        return $this;
    }

    public function setFilterDomain(?string $value): self
    {
        $this->filters['domain'] = $value;
        return $this;
    }

    /**
     * @param  DataCollection|PlanFactGoalData[]  $goals
     */
    private function setFilterGoals(\Spatie\LaravelData\DataCollection|array $goals): self
    {
        $this->filters['goals'] = array_map(static fn ($goal): mixed => $goal['id'], $goals->toArray());
        return $this;
    }

    public function addAllCabinets(): self
    {
        /** @var Source[] $sources */
        $sources = $this->campaign
            ->cabinetSources()
            ->get();

        foreach ($sources as $source) {
            $this->addCabinetSource($source);
        }

        return $this;
    }

    public function addCabinets(array $cabinets): self
    {
        /** @var Source[] $sources */
        $sources = $this->campaign
            ->sources()
            ->whereIn('settings_type', $cabinets)
            ->get();

        foreach ($sources as $source) {
            $this->addCabinetSource($source);
        }

        return $this;
    }

    public function addCabinet(string $cabinet): self
    {
        if (in_array($cabinet, $this->filters['cabinets'], true)) {
            return $this;
        }

        /** @var Source $source */
        $source = $this->campaign
            ->sources()
            ->where('settings_type', $cabinet)
            ->first();

        if (!is_null($source)) {
            $this->addCabinetSource($source);
        }

        return $this;
    }

    public function addCabinetSource(Source $source): self
    {
        $service = match ($source->settings_type) {
            Source::TYPE_YANDEX_DIRECT => new YandexDirectCabinetSourceService($source),
            Source::TYPE_VK => new VkCabinetSourceService($source),
            // ...
            default => null,
        };

        if (is_null($service)) {
            return $this;
        }

        $this->cabinetServices[] = $service;
        $this->filters['cabinets'][] = $source->settings_type;

        if ($source->settings_type === Source::TYPE_VK) {
            $this->addMetricsSource($source);
        }

        return $this;
    }

    public function getCabinets(): array
    {
        return $this->cabinetServices;
    }

    public function getDomains(): array
    {
        $res = [];
        foreach ($this->statsServices as $service) {
            $res[] = $service->getDomains($this->period ?? null, $this->filters);
        }
        return array_merge(...$res);
    }

    public function getCampaigns(): array
    {
        $lst = collect();

        foreach ($this->getCabinets() as $cabinetService) {
            $lst = $lst->merge($cabinetService->getCampaigns());
        }

        return $lst->unique()->values()->toArray();
    }

    public function getCampaignUtms(): array
    {
        $res = [];
        foreach ($this->statsServices as $service) {
            $res[] = $service->getCampaignUtms();
        }
        return array_merge(...$res);
    }

    public function getSourceUtms(): array
    {
        $res = [];
        foreach ($this->statsServices as $service) {
            $res[] = $service->getSourceUtms();
        }
        return array_merge(...$res);
    }

    public function getMediumUtms(): array
    {
        $res = [];
        foreach ($this->statsServices as $service) {
            $res[] = $service->getMediumUtms();
        }
        return array_merge(...$res);
    }

    public function getDevices(): array
    {
        $res = [];
        foreach ($this->statsServices as $service) {
            $res[] = $service->getDevices($this->period ?? null, $this->filters);
        }
        return array_merge(...$res);
    }

    /**
     * @throws InvalidDataCollectionModification
     */
    public function getResult(): DataCollection
    {
        return app(GetPlanFactDataAction::class)->execute(
            campaign: $this->campaign,
            statsServices: $this->statsServices,
            ecommerceServices: $this->ecommerceServices,
            cabinetServices: $this->getCabinets(),
            period: $this->period,
            filters: $this->filters,
            planSettings: $this->plan,
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign\PlanFact;

use App\Exceptions\BusinessException;
use App\Infrastructure\DateRange;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JsonException;
use Module\Campaign\Models\Campaign;
use Module\PlanFact\Models\PlanSettings;
use Module\PlanFact\Services\PlanFactBuilder;

final class PlanFactInitController
{
    /**
     * @throws BusinessException
     * @throws JsonException
     */
    public function __invoke(Request $request, Campaign $campaign): ?array
    {
        $builder = PlanFactBuilder::create($campaign);

        $selectedPeriod = self::getSelectedPeriod($request);
        $builder->setPeriod($selectedPeriod);

        /** @var Collection|PlanSettings[] $plans */
        $plans = $campaign->planSettings()
            ->get();

        if ($plans->count() === 0) {
            return abort(404, 'Plans not found.');
        }

        $selectedPlanId = (int)$request->input('plan_id');
        $selectedPlan = $plans->first(static fn ($item): bool => $item->id === $selectedPlanId);
        if (!$selectedPlan) {
            $selectedPlan = $plans->first();
        }
        $builder->setPlan($selectedPlan);

        $selectedDomain = (string)$request->input('domain');
        $availableDomains = $builder->getDomains();
        if (!in_array($selectedDomain, $availableDomains, true)) {
            $selectedDomain = null;
        }

        if (!empty($selectedDomain)) {
            $builder->setFilterDomain($selectedDomain);
        }

        $result = $builder->getResult();

        return [
            'selectedPeriod' => $selectedPeriod->toArray(),

            'plans' => $plans->map(static fn ($plan) => $plan->only(['id', 'name'])),
            'selectedPlan' => $selectedPlan,

            'availableDomains' => $availableDomains,
            'selectedDomain' => $selectedDomain,

            'items' => $result->toArray(),
            /* [[title, plan, fact, ?units, linear[[plan, fact], ...]], ...] */
        ];
    }

    private static function getSelectedPeriod(Request $request, string $key = 'period'): DateRange
    {
        return DateRange::make($request->input($key, '7days'));
    }
}

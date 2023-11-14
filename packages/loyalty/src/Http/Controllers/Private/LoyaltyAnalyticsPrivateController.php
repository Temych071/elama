<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Private;

use App\Infrastructure\DateRange;
use Illuminate\Support\Arr;
use Loyalty\Models\LoyaltyCardTransaction;
use stdClass;

final class LoyaltyAnalyticsPrivateController extends AbstractLoyaltyPrivateController
{
    protected string $pageComponent = 'Loyalty/Private/LoyaltyAnalytics';

    protected function getPageData(): array
    {
        $dateRange = DateRange::make(\Request::input('dateRange', '7days'));
        $dateRangePrev = $dateRange->getPrev();

        return [
            ...parent::getPageData(),
            'dateRange' => $dateRange,
            'dateRangePrev' => $dateRangePrev,
            'chartGroupType' => \Request::input('chartGroupType', '1day'),

            'summary' => [
                'revenue' => $this->getRevenueStats($dateRange),
                'expenses' => $this->getExpensesStats($dateRange),
                'cards_count' => $this->getCardsCountStats($dateRange),
                'usage_percent' => $this->getUsagePercentStats($dateRange),
            ],
            'summaryPrev' => [
                'revenue' => $this->getRevenueStats($dateRangePrev),
                'expenses' => $this->getExpensesStats($dateRangePrev),
                'cards_count' => $this->getCardsCountStats($dateRangePrev),
                'usage_percent' => $this->getUsagePercentStats($dateRangePrev),
            ],

            'genders' => $this->getGendersStats(),
            'ages' => $this->getAgesStats(),
            'clientsQuality' => $this->getClientsQualityStats(),
            'segments' => $this->getSegmentsStats(),
        ];
    }

    private function getSegmentsStats(): ?array
    {
        $q = $this->getLoyalty()
            ->transactions()
            ->where('type', 'purchase')
            ->groupBy('loyalty_cards.loyalty_id')
            ->selectRaw('
                SUM(cheque_cost) as revenue,
                SUM(cheque_cost) / COUNT(cheque_cost) as avg_cheque,
                -SUM(bonuses_amount) / COUNT(cheque_cost) as cpo
            ')->toBase();
        $cardIdsQuery = $this->getLoyalty()
            ->transactions()
            ->groupBy('loyalty_card_id')
            ->select('loyalty_card_id')
            ->distinct();

        $prepareSegment = static function (string $title, string $desc, $summary) {
            $summary = (array)$summary;

            if (empty($summary)) {
                return null;
            }

            return [
                'title' => $title,
                'desc' => $desc,
                'summary' => [
                    'revenue' => $summary['revenue'],
                    'avg_cheque' => $summary['avg_cheque'],
                    'cpo' => $summary['cpo'],
                ],
            ];
        };

        return [
            'total' => $prepareSegment('Все', 'Все клиенты', $q->clone()->first()),
            'segments' => array_filter([
                $prepareSegment(
                    'Тёплые', 'Последняя покупка менее 10 дней назад',
                    $q->clone()->whereIn('loyalty_card_id', $cardIdsQuery->clone()
                        ->havingRaw('TIMESTAMPDIFF(DAY, MAX(date), NOW()) <= 10'),
                    )->first(),
                ),
                $prepareSegment(
                    'Холодные', 'Последняя покупка Менее 60 дней назад',
                    $q->clone()->whereIn('loyalty_card_id', $cardIdsQuery->clone()
                        ->havingRaw('TIMESTAMPDIFF(DAY, MAX(date), NOW()) BETWEEN 11 AND 59'),
                    )->first(),
                ),
                $prepareSegment(
                    'Спящие', 'Последняя покупка более 60 дней назад',
                    $q->clone()->whereIn('loyalty_card_id', $cardIdsQuery->clone()
                        ->havingRaw('TIMESTAMPDIFF(DAY, MAX(date), NOW()) >= 60'),
                    )->first(),
                ),
            ]),
        ];
    }

    private function getClientsQualityStats(): array
    {
        return (array)$this->getLoyalty()
            ->forms()
            ->toBase()
            ->selectRaw('
                COUNT(name) AS name,
                COUNT(gender) AS gender,
                COUNT(birthday) AS birthday,
                COUNT(email) AS email,
                COUNT(sms_notifications) AS sms_notifications,
                COUNT(email_notifications) AS email_notifications,
                COUNT(*) AS total
            ')
            ->first();
    }

    private function getGendersStats(): array
    {
        return [
            'male' => 0,
            'female' => 0,
            'total' => $this->getLoyalty()->forms()->count(),

            ...$this->getLoyalty()->forms()
                ->groupBy('gender')
                ->whereNotNull('gender')
                ->selectRaw('gender, COUNT(*) AS cnt')
                ->pluck('cnt', 'gender'),
        ];
    }

    private function getAgesStats(): array
    {
        return $this->getLoyalty()
            ->forms()
            ->toBase()
            ->whereNotNull('birthday')
            ->groupBy('age')
            ->selectRaw('TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age, COUNT(*) AS count')
            ->pluck('count', 'age')
            ->toArray();
    }

    /**
     * @return array{summary: numeric, chart: array{date: string, value: numeric}}
     */
    private function getRevenueStats(DateRange $dateRange): array
    {
        $loyalty = $this->getLoyalty();

        $q = $loyalty->transactions()
            ->whereInDateRange($dateRange)
            ->where('bonuses_amount', '<', 0)
            ->where('type', 'purchase')
            ->selectRaw('SUM(cheque_cost) AS revenue')
            ->toBase();

        return [
            'summary' => (int)($q->clone()->first()?->revenue ?? 0),
            'chart' => $this->groupChart($dateRange,
                $q->clone()->selectRaw('DATE(date) AS date')
                    ->groupBy('date')
                    ->get()
                    ->mapWithKeys(static fn($item) => [(string)$item->date => (int)$item->revenue])
                    ->toArray()
            ),
            'total' => (int)$loyalty->transactions()->sum('cheque_cost'),
        ];
    }

    /**
     * @return array{summary: numeric, chart: array{date: string, value: numeric}}
     */
    private function getExpensesStats(DateRange $dateRange): array
    {
        $loyalty = $this->getLoyalty();

        $q = $loyalty->transactions()
            ->whereInDateRange($dateRange)
            ->where('bonuses_amount', '<', 0)
            ->where('type', 'purchase')
            ->selectRaw('-SUM(bonuses_amount) AS expenses')
            ->toBase();

        return [
            'summary' => -$q->clone()->sum('bonuses_amount'),
            'chart' => $this->groupChart($dateRange,
                $q->clone()->selectRaw('DATE(date) AS date')
                    ->groupBy('date')
                    ->get()
                    ->mapWithKeys(static fn($item) => [(string)$item->date => (int)$item->expenses])
                    ->toArray()
            ),
            'revenue' => (int)$loyalty->transactions()
                ->whereInDateRange($dateRange)
                ->sum('cheque_cost'),
        ];
    }

    /**
     * @return array{summary: numeric, chart: array{date: string, value: numeric}}
     */
    private function getCardsCountStats(DateRange $dateRange): array
    {
        $loyalty = $this->getLoyalty();

        $q = $loyalty->forms()
            ->whereInDateRange($dateRange, 'loyalty_forms.created_at')
            ->selectRaw('COUNT(*) as cards_count')
            ->toBase();

        return [
            'summary' => $q->clone()->count(),
            'chart' => $this->groupChart($dateRange,
                $q->clone()->selectRaw('DATE(loyalty_forms.created_at) AS date')
                    ->groupBy('date')
                    ->get()
                    ->mapWithKeys(static fn($item) => [(string)$item->date => (int)$item->cards_count])
                    ->toArray(),
            ),
            'total' => $loyalty->forms()->count(),
        ];
    }

    /**
     * @return array{summary: numeric, chart: array{date: string, value: numeric}}
     */
    private function getUsagePercentStats(DateRange $dateRange): array
    {
        $loyalty = $this->getLoyalty();

        $cardsCount = $loyalty->forms()->count();

        $q = $loyalty->transactions()
            ->whereInDateRange($dateRange)
            ->where('type', 'purchase')
            ->selectRaw('COUNT(DISTINCT(loyalty_card_id)) AS usages')
            ->toBase();

        return [
            'summary' => $cardsCount ? (int)($q->clone()->first()?->usages ?? 0) / $cardsCount : 0,
            'chart' => $this->groupChart($dateRange,
                $q->clone()->selectRaw('DATE(date) AS date')
                    ->groupBy('date')
                    ->get()
                    ->mapWithKeys(static fn($item) => [(string)$item->date => (int)$item->usages])
                    ->toArray(),
                static fn(array $values) => $cardsCount ? array_sum($values) / $cardsCount : 0,
            ),
            'perYear' => $cardsCount ? (int)$loyalty->transactions()
                    ->whereInDateRange(DateRange::parseFromAlias('year'))
                    ->distinct()
                    ->count('loyalty_card_id') / $cardsCount : 0,
        ];
    }

    private function groupChart(DateRange $dateRange, array $data, ?callable $groupCallback = null): array
    {
        $groupCallback ??= static fn(array $items) => array_sum($items);
        $dates = array_chunk($dateRange->getDaysWithFormat(), $this->getGroupDaysCount());

        return array_reduce($dates, static function ($res, $dates) use ($data, $groupCallback) {
            $res[DateRange::fromArray($dates)->formatByPreset()] = $groupCallback(Arr::only($data, $dates));
            return $res;
        }, []);
    }

    private function getGroupDaysCount(): int
    {
        return match (\Request::input('chartGroupType', '1day')) {
            '1day' => 1,
            '3days' => 3,
            '7days' => 7,
        };
    }
}

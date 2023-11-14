<?php

declare(strict_types=1);

namespace Reviews\Parsers\Yandex\Services;

use App\Infrastructure\DateRange;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Reviews\Parsers\Contracts\ReviewsService;
use Reviews\Parsers\Contracts\StatsService;
use Reviews\Parsers\Dto\ExternalReview;
use Reviews\Parsers\Dto\ExternalReviewAnswer;
use Reviews\Parsers\Dto\ExternalStats;
use Reviews\Parsers\Dto\GeneralSourceData;
use Reviews\Parsers\Exceptions\PlaceForbiddenException;
use Reviews\Parsers\Exceptions\PlaceNotFoundException;
use Reviews\Parsers\Exceptions\UnexpectedReviewsException;
use Reviews\Parsers\Yandex\DTO\YandexCompany;

final class YandexReviewsService implements ReviewsService, StatsService
{
    protected const MAX_PAGES = 3;

    /**
     * @param  callable(PendingRequest): Response  $cb
     * @return Response
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws PlaceForbiddenException
     * @throws ConnectionException
     */
    protected function requestWrapper(callable $cb): Response
    {
        return app(YandexReviewRequestProxy::class)->handle($cb);
    }

    /**
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws ConnectionException
     * @throws PlaceForbiddenException
     */
    public function getGeneralData(string $placeId): GeneralSourceData
    {
        $res = $this->requestWrapper(static fn(PendingRequest $q) => $q
            ->baseUrl('')
            ->maxRedirects(1)
            ->get("https://yandex.ru/sprav/$placeId/p/edit/reviews/"))
            ->body();

        if (preg_match('#<div\s+class="ReviewsPage-HeadingReviewsCount"\s*>\s*(\d+)\s+отзыв(|ов|а)\s*</div>#u', $res,
            $matches)) {
            $totalReviewsCount = (int) $matches[1];
        } else {
            throw new UnexpectedReviewsException($res);
        }

        // <div class="RatingCard-RatingNumber">1.0</div>
        if (preg_match('#<div\s+class="RatingCard-RatingNumber"\s*>\s*(\d+\.\d+)\s*</div>#', $res, $matches)) {
            $rating = (float) $matches[1];
        } else {
            throw new UnexpectedReviewsException($res);
        }

        return new GeneralSourceData(
            rating: $rating,
            totalReviewsCount: $totalReviewsCount,
        );
    }

    /**
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws ConnectionException
     * @throws PlaceForbiddenException
     */
    protected function getReviewsRaw(string $placeId, int $page): Response
    {
        return $this->requestWrapper(fn(PendingRequest $r) => $r->get("$placeId/reviews", [
            'ranking' => 'by_time',
            'page' => $page,
        ]));
    }

    /**
     * @return ExternalReview[]
     *
     * @throws PlaceForbiddenException
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws ConnectionException
     */
    public function getReviews(string $placeId, ?DateRange $dateRange = null): array
    {
        $reviews = [];
        $page = 1;

        while (true) {
            $res = $this->getReviewsRaw($placeId, $page)->json()['list'] ?? null;

            if (empty($res)) {
                break;
            }

            foreach ($res['items'] ?? [] as $item) {
                $date = Carbon::createFromTimestampMsUTC($item['time_created']);

                if ($dateRange !== null) {
                    if ($dateRange->isBefore($date)) {
                        break 2;
                    }
                    if ($dateRange->isAfter($date)) {
                        continue;
                    }
                }

                $reviews[] = new ExternalReview(
                    id: $item['id'],
                    rating: $item['rating'],
                    name: $item['author']['user'],
                    text: $item['full_text'],
                    created_at: $date,
                    answer: optional($item['owner_comment'] ?? null, static fn($data) => new ExternalReviewAnswer(
                        text: $data['text'],
                        created_at: Carbon::createFromTimestampMsUTC($data['time_created']),
                    )),
                );
            }

            if ($res['pager']['limit'] + $res['pager']['offset'] >= $res['pager']['total']) {
                break;
            }

            ++$page;
            if ($page > self::MAX_PAGES) {
                break;
            }

            sleep(1);
        }

        return $reviews;
    }

    /**
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws ConnectionException
     * @throws PlaceForbiddenException
     */
    public function getStats(string $placeId, DateRange $dateRange): array
    {
        $csrfRes = $this->requestWrapper(fn(PendingRequest $q) => $q->baseUrl('')
            ->get('https://yandex.ru/business/statistic/api/metrika/get-vacuum-statistic'));

        $res = $this->requestWrapper(fn(PendingRequest $q) => $q->baseUrl('')
            ->withOptions(['cookies' => $csrfRes->cookies])
            ->get('https://yandex.ru/business/statistic/api/metrika/get-vacuum-statistic', [
                'csrfToken' => $csrfRes->json('csrfToken'),
                'permalink' => $placeId,
                'group' => 'Day',
                'from' => $dateRange->getFrom()->format('Y-m-d'),
                'to' => $dateRange->getTo()->format('Y-m-d'),
            ]));

        $data = $res->json('data');

        // https://yandex.ru/business/statistic/api/metrika/get-vacuum-statistic?csrfToken=c17b8201e561866d19b1f081967fefaa9cd709b6:1689675264150&permalink=166812884275&from=2023-07-11&to=2023-07-18&group=Week
        // https://yandex.ru/business/statistic/api/statistic/get-deep-clicks?csrfToken=c17b8201e561866d19b1f081967fefaa9cd709b6:1689675264150&permalink=166812884275&from=2023-07-11&to=2023-07-18&aggregation=Week
        // https://yandex.ru/business/statistic/api/statistic/get-discovery-clicks?csrfToken=c5c871e403a3dd97ba4b0f9f81a157a398439bc5%3A1689690251962&permalink=166812884275&from=2023-07-11&to=2023-07-18&aggregation=Week

        if (empty($data['allDates'] ?? null) || empty($data['values'] ?? null)) {
            throw new UnexpectedReviewsException($res->body());
        }

        $stats = [];
        foreach ($data['allDates'] as $date) {
            $stats[] = new ExternalStats(
                date: Carbon::parse($date),
                views: $data['values']['org-show'][$date],
                calls: $data['values']['extended-call'][$date],
                site: $data['values']['site'][$date],
                routes: $data['values']['route'][$date],
            );
        }

        return $stats;
    }

    /**
     * @return array
     * @throws ConnectionException
     * @throws PlaceForbiddenException
     * @throws PlaceNotFoundException
     * @throws UnexpectedReviewsException
     */
    public function getCompanies(): array
    {
        $companies = [];

        $page = 1;
        while (true) {
            $res = $this->requestWrapper(fn(PendingRequest $r) => $r->get('companies', [
                'limit' => 'a',
                'page' => $page,
            ]))->json();

            if (empty($res['listCompanies'])) {
                break;
            }

            foreach ($res['listCompanies'] as $company) {
                $companies[] = new YandexCompany(
                    id: $company['id'],
                    name: $company['displayName'],
                    logo_url: $company['logo'] ?? null,
                );
            }

            if ($res['page'] * $res['limit'] >= $res['total']) {
                break;
            }

            ++$page;
        }

        return $companies;
    }

    public function getCompaniesCached(): array
    {
        return Cache::remember(
            'reviews.external.yandex.companies',
            5 * 60,
            fn() => $this->getCompanies(),
        );
    }

    /**
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws ConnectionException
     */
    protected function fetchCompanyPage(int $companyId): ?YandexCompany
    {
        try {
            $res = $this->requestWrapper(static fn(PendingRequest $q) => $q
                ->baseUrl('')
                ->maxRedirects(1)
                ->get("https://yandex.ru/sprav/$companyId/p/edit/reviews/"))
                ->body();
        } catch (PlaceForbiddenException) {
            return null;
        }

        preg_match('/<h4\s+class=".*ya-business-label_level_h4.*"\s*>\s*(.+)\s*<span\s+class="ya-business-company-plate__last-word"\s*>\s*(.+)\s*<div/iU', $res, $matches);
        array_shift($matches);
        $companyName = implode(' ', $matches);

        preg_match('/<img\s+class="ya-business-geoadv-company-logo__logo-image"\s+src="(.+)"\s*\/>/iU', $res, $matches);
        $companyLogoUrl = $matches[1];

        return new YandexCompany($companyId, $companyName, $companyLogoUrl);
    }

    /**
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws ConnectionException
     */
    public function findCompanyCached(int $companyId): ?YandexCompany
    {
        $company = Arr::first(
            $this->getCompaniesCached(),
            static fn(YandexCompany $company) => $company->id === $companyId,
            null,
        );

        if ($company === null) {
            $company = $this->fetchCompanyPage($companyId);
        }

        return $company;
    }

    /**
     * @throws PlaceForbiddenException
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws ConnectionException
     */
    public function getCsrfToken(): string
    {
        $res = $this->requestWrapper(fn(PendingRequest $r) => $r
            ->baseUrl('')
            ->get('https://yandex.ru/business/boost'))
            ->body();

        // 9b6db4d2963078bc1ecdf0ffdeded702dd9642ec:1687188569994
        preg_match('/"csrfToken"\s*:\s*"(.+)"\s*,/Ui', $res, $matches);

        $csrf = $matches[1] ?? null;
        if (empty($csrf)) {
            throw new UnexpectedReviewsException($res);
        }

        return $csrf;
    }
}

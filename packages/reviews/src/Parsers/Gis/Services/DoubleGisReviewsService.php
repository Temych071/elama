<?php

namespace Reviews\Parsers\Gis\Services;

use App\Infrastructure\DateRange;
use Error;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Reviews\Parsers\Contracts\ReviewsService;
use Reviews\Parsers\Dto\ExternalReview;
use Reviews\Parsers\Dto\ExternalReviewAnswer;
use Reviews\Parsers\Dto\GeneralSourceData;
use Reviews\Parsers\Dto\Review;
use Reviews\Parsers\Dto\ReviewsList;

final class DoubleGisReviewsService implements ReviewsService
{
    protected const MAX_PAGES = 3;

    protected readonly ?string $apiKey;
    protected const CACHE_KEY = 'review-gis-';
    protected const MAX_LIMIT = 50; // Выяснено перебором)

    public function __construct(private readonly int $cacheTtl = 86400)
    {
        $this->apiKey = config('services.2gis.reviews_api_key');

        if (empty($this->apiKey)) {
            throw new Error('API key for 2GIS not specified.');
        }
    }

    public function getInfo($placeId): ?ReviewsList
    {
        return Cache::remember(self::CACHE_KEY.$placeId, $this->cacheTtl, function () use ($placeId): ReviewsList {
            $url = "https://public-api.reviews.2gis.com/2.0/branches/$placeId/reviews";

            $res = Http::timeout(10)
                ->get($url, [
                    'fields' => implode(',', [
                        'meta.branch_rating',
                        'meta.branch_reviews_count',
                    ]),
                    'is_advertiser' => 'false',
                    'limit' => 10,
                    'sort_by' => 'date_edited',
                    'rated' => 'true',
                    'locale' => 'ru_RU',
                    'key' => $this->apiKey,
                ])
                ->throw()
                ->json();

            return ReviewsList::from([
                'rating' => $res['meta']['branch_rating'] ?? 0,
                'totalCount' => $res['meta']['branch_reviews_count'] ?? 0,
                'reviews' => Review::collection(
                    array_map(static fn($review): array => [
                        'id' => $review['id'],
                        'text' => $review['text'],
                        'date' => $review['date_created'],
                        'name' => $review['user']['name'],
                        'rating' => $review['rating'],
                    ], $res['reviews'])
                ),
            ]);
        });
    }

    /**
     * @throws RequestException
     */
    public function getReviews(string $placeId, ?DateRange $dateRange): array
    {
        $reviews = [];

        $url = "https://public-api.reviews.2gis.com/2.0/branches/$placeId/reviews?".http_build_query([
                'fields' => 'meta.branch_reviews_count',
                'is_advertiser' => 'false',
                'limit' => self::MAX_LIMIT,
                'sort_by' => 'date_edited',
                'rated' => 'true',
                'locale' => 'ru_RU',
                'key' => $this->apiKey,
                ...($dateRange === null ? [] : [
                    'offset_date' => $dateRange->getTo()->toISOString(),
                ]),
            ]);

        $pageCounter = 1;
        while (true) {
            $res = Http::get($url)
                ->throw()
                ->json();

            foreach ($res['reviews'] as $item) {
                $created_at = Carbon::parse($item['date_created']);

                if ($dateRange !== null && !$dateRange->includes($created_at)) {
                    break 2;
                }

                $reviews[] = new ExternalReview(
                    id: $item['id'],
                    rating: $item['rating'],
                    name: $item['user']['name'],
                    text: $item['text'],
                    created_at: $created_at,
                    answer: empty($item['official_answer']) ? null : new ExternalReviewAnswer(
                        text: $item['official_answer']['text'],
                        created_at: Carbon::parse($item['official_answer']['date_created']),
                    ),
                );
            }

            $url = $res['meta']['next_link'] ?? null;
            ++$pageCounter;

            if (
                $pageCounter > self::MAX_PAGES
                || empty($url)
            ) {
                break;
            }

            sleep(1);
        }

        return $reviews;
    }

    public function getGeneralData(string $placeId): GeneralSourceData
    {
        $res = Http::get("https://public-api.reviews.2gis.com/2.0/branches/$placeId/reviews", [
            'fields' => implode(',', [
                'meta.branch_rating',
                'meta.branch_reviews_count',
            ]),
            'limit' => 1,
            'key' => $this->apiKey,
        ])
            ->throw()
            ->json();

        return new GeneralSourceData(
            rating: $res['meta']['branch_rating'] ?? 0.0,
            totalReviewsCount: $res['meta']['branch_reviews_count'] ?? 0,
        );
    }

//    public function getAnswerSender(): ?ReviewAnswerSender
//    {
//        return null;
//    }
}

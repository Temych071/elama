<?php

declare(strict_types=1);

namespace Reviews\Parsers\Gis\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Reviews\Parsers\Dto\ExternalReviewAnswer;

final class DoubleGisAnswerApiService extends DoubleGisBaseApiService
{
    /**
     * @param  string  $placeId
     * @param  string  $reviewId
     * @param  string  $text
     * @return bool
     * @throws RequestException
     */
    public function send(string $placeId, string $reviewId, string $text): bool
    {
        return $this->prepareRequest()
            ->post("presence/reviews/$reviewId/comments", [
                'isOfficialAnswer' => true,
                'catalog' => '2gis',
                'text' => $text,
            ])
            ->throw()
            ->ok();
    }

    /**
     * @throws RequestException
     */
    public function delete(string $placeId, string $reviewId): bool
    {
        $answerId = $this->getMainAnswer($reviewId)['id'] ?? null;

        if ($answerId === null) {
            return true;
        }

        return $this->prepareRequest()
            ->withQueryParameters([
                'type' => 'reply',
                'catalog' => '2gis',
            ])
            ->delete("presence/reviews/$reviewId/comments/$answerId")
            ->throw()
            ->ok();
    }

    public function getMainAnswer(string $reviewId): ?array
    {
        $answers = $this->prepareRequest()
            ->withQueryParameters([
                'catalog' => '2gis',
                'limit' => 200,
            ])
            ->get("presence/reviews/$reviewId/comments")
            ->throw()
            ->json('result.items');

        return Arr::first($answers, static fn(array $answer) => $answer['isOfficialAnswer'] ?? false) ?? null;
    }

    /**
     * @throws RequestException
     */
    public function edit(string $placeId, string $reviewId, string $text): bool
    {
        return $this->delete($placeId, $reviewId)
            && $this->send($placeId, $reviewId, $text);
    }
}

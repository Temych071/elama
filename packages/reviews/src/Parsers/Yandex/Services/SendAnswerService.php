<?php

declare(strict_types=1);

namespace Reviews\Parsers\Yandex\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Reviews\Parsers\Exceptions\PlaceForbiddenException;
use Reviews\Parsers\Exceptions\PlaceNotFoundException;
use Reviews\Parsers\Exceptions\UnexpectedReviewsException;
use Reviews\Parsers\Yandex\Exceptions\NotFoundReviewOnPageForAnswerException;

final class SendAnswerService
{
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
     * @throws NotFoundReviewOnPageForAnswerException
     */
    public function send(string $placeId, string $reviewId, string $answerText, int $page): bool
    {
        $limit = 20; // Не меняется, чтоб не парсить пусть хардкод

        $reviewsRes = $this->requestWrapper(static fn(PendingRequest $q) => $q
            ->baseUrl('')
            ->maxRedirects(1)
            ->get("https://yandex.ru/sprav/$placeId/p/edit/reviews/", [
                'page' => $page,
                'ranking' => 'by_time',
            ]));

        $reviewsBody = $reviewsRes->body();

        preg_match('/"id":\s*"'.$reviewId.'"(.|\d)*"business_answer_csrf_token":\s*"(.+)"/U', $reviewsBody, $matches);
        if (empty($matches[2] ?? null)) {
            throw new NotFoundReviewOnPageForAnswerException();
        }
        $answerCsrfToken = $this->decodeJsonSpecChars($matches[2]);

        preg_match('/"csrf"\s*:\s*"(.+)"/iU', $reviewsBody, $matches);
        $csrfToken = $this->decodeJsonSpecChars($matches[1]);

        preg_match('/"csrf_token"\s*:\s*"(.+)"/iU', $reviewsBody, $matches);
        $reviewsCsrfToken = $this->decodeJsonSpecChars($matches[1]);

        return $this->requestWrapper(static fn(PendingRequest $q) => $q
            ->withOptions(['cookies' => $reviewsRes->cookies])
            ->withHeaders(['X-Csrf-Token' => $csrfToken])
            ->asJson()
            ->post("ugcpub/business-answer", [
                'reviewId' => $reviewId,
                'reviewsCsrfToken' => $reviewsCsrfToken,
                'answerCsrfToken' => $answerCsrfToken,
                'text' => $answerText,
            ]))
            ->ok();
    }

    private function decodeJsonSpecChars(string $str): string
    {
        return json_decode('"'.$str.'"', false, 512, JSON_THROW_ON_ERROR);
    }
}

<?php

declare(strict_types=1);

namespace Reviews\Parsers\Yandex\Services;

use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;
use Reviews\Parsers\Exceptions\PlaceForbiddenException;
use Reviews\Parsers\Exceptions\PlaceNotFoundException;
use Reviews\Parsers\Exceptions\UnexpectedReviewsException;

final class YandexReviewRequestProxy
{
    public function __construct(
        private readonly YandexSessionService $sessionService,
    ) {
    }

    /**
     * @throws UnexpectedReviewsException
     * @throws PlaceNotFoundException
     * @throws ConnectionException
     * @throws PlaceForbiddenException
     */
    public function handle(callable $cb)
    {
        /** @var ?Response $res */
        $res = null;

        try {
            $res = $cb($this->createRequest());

            if ($res->ok()) {
                return $res;
            }
        } catch (ConnectionException $e) {
            if (!Str::startsWith($e->getMessage(), 'cURL error 52')) {
                throw $e;
            }
        } catch (TooManyRedirectsException $e) {
            if ($e->getResponse() && !$this->isAuthUri(new Uri($e->getResponse()->getHeaderLine('Location')))) {
                throw $e;
            }
        }

        if ( // reset auth session on redirect
            $res === null
            || $res->redirect()
            || $this->isAuthUri($res->effectiveUri())
        ) {
            $this->sessionService->resetSession();
            $res = $cb($this->createRequest());
        }

        if ($res->notFound() && $res->json('permanent_id')) {
            throw new PlaceNotFoundException($res->json('permanent_id'), $res->body());
        }

        if ($res->forbidden() && $res->json('permanent_id')) {
            throw new PlaceForbiddenException($res->json('permanent_id'), $res->body());
        }

        if ($res->ok()) {
            return $res;
        }

        Log::error('Yandex reviews API error request', [
            'request' => [
                'body' => $res->transferStats->getRequest()->getBody(),
                'headers' => $res->transferStats->getRequest()->getHeaders(),
                'url' => $res->transferStats->getRequest()->getUri(),
            ],
        ]);
        throw new UnexpectedReviewsException($res->body());
    }

    protected function createRequest(): PendingRequest
    {
        return $this->sessionService
            ->getPendingRequest()
            ->withoutRedirecting()
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
            ])
            ->baseUrl('https://yandex.ru/sprav/api/');
    }

    protected function isAuthUri(UriInterface $uri): bool
    {
        return (
            Str::contains($uri->getHost(), 'passport.yandex.ru')
            && Str::contains($uri->getPath(), 'auth')
        );
    }
}

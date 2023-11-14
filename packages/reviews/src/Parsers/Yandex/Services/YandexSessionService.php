<?php

declare(strict_types=1);

namespace Reviews\Parsers\Yandex\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Reviews\Parsers\Yandex\Exceptions\YandexAuthException;

final class YandexSessionService
{
    public const SESSION_ID_CACHE_KEY = 'reviews.parsers.yandex.service_account.session_id';
    public const SESSION_COOKIE_NAME = 'Session_id';
    public const SESSION_COOKIE_DOMAIN = '.yandex.ru';

    protected readonly string $login;
    protected readonly string $password;

    public function __construct()
    {
        $this->login = config('services.yandex.service_account.login');
        $this->password = config('services.yandex.service_account.password');
    }

    public function getSession(): string
    {
        return Cache::remember(self::SESSION_ID_CACHE_KEY, null, function () {
            $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36';

            $res = Http::withHeaders([
                'User-Agent' => $userAgent,
            ])->get('https://passport.yandex.ru/auth');
            preg_match('#<input\s+type="hidden"\s+name="csrf_token"\s+value="(.+?)"\s*/>#', $res->body(), $matches);
            $csrf = $matches[1];

            $res = Http::withHeaders([
                'User-Agent' => $userAgent,
            ])->withOptions([
                'cookies' => $res->cookies,
            ])->asForm()->post('https://passport.yandex.ru/registration-validations/auth/multi_step/start', [
                'csrf_token' => $csrf,
                'login' => $this->login,
            ]);

            $res = Http::withHeaders([
                'User-Agent' => $userAgent,
            ])->withOptions([
                'cookies' => $res->cookies,
            ])->asForm()->post('https://passport.yandex.ru/registration-validations/auth/multi_step/commit_password', [
                'csrf_token' => $csrf,
                'track_id' => $res->json('track_id'),
                'lang' => 'ru',
                'password' => $this->password,
            ]);

            $sessionId = $res->cookies->getCookieByName(self::SESSION_COOKIE_NAME)?->getValue();

            if (empty($sessionId)) {
                throw new YandexAuthException();
            }

            return $sessionId;
        });
    }

    public function resetSession(): void
    {
        Cache::forget(self::SESSION_ID_CACHE_KEY);
    }

    public function getPendingRequest(): PendingRequest
    {
        return Http::withCookies([self::SESSION_COOKIE_NAME => $this->getSession()], self::SESSION_COOKIE_DOMAIN);
    }
}

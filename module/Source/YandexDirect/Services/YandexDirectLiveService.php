<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Services;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;
use Module\Source\Sources\Models\SourceAuthToken;
use Module\Source\YandexDirect\Actions\RefreshYandexAuthTokenAction;
use Module\Source\YandexDirect\Exceptions\YandexDirectException;
use Module\Source\YandexDirect\Exceptions\YandexDirectInvalidOAuthTokenException;

final class YandexDirectLiveService
{
    public function __construct(
        private SourceAuthToken $token,
    ) {
    }

    public function accountManagement_Get(array $accounts = [], bool $isLogins = false): array
    {
        $query = [];
        if ($accounts !== []) {
            $query = [
                'SelectionCriteria' => [
                    ($isLogins ? 'Logins' : 'AccountIDS') => $isLogins
                        ? $accounts
                        : array_map(static fn ($id): int => (int)$id, $accounts),
                ],
            ];
        }

        return $this->accountManagement('Get', $query)['Accounts'];
    }

    private function accountManagement(string $action, array $query = [], array $headers = []): array
    {
        return $this->post('AccountManagement', array_merge($query, ['Action' => $action]), $headers);
    }

    private function _post(
        string $method,
        array $query = [],
        array $headers = [],
    ): array {
        $query = [
            'param' => $query,
            'token' => $this->token->token,
            'method' => $method,
        ];

        $res = Http::withHeaders(array_merge($this->getHeaders(), $headers))
            ->post(env('YANDEX_DIRECT_API_V4_URL', 'https://api.direct.yandex.ru/live/v4/json/'), $query)
            ->throw()
            ->json();

        YandexDirectException::throwIfError($res);
        return $res['data'];
    }

    #[ArrayShape(['Accept-Language' => "string", 'Content-Type' => "string"])]
    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept-Language' => config('app.locale'),
        ];
    }

    /**
     * @param  string  $method
     * @param  array  $query
     * @param  array  $headers
     * @return array
     * @throws BusinessException
     */
    private function post(
        string $method,
        array $query = [],
        array $headers = [],
    ): array
    {
        $this->checkAndRefreshAuthToken();

        try {
            return $this->_post(...func_get_args());
        } catch (YandexDirectInvalidOAuthTokenException) {
        }

        $this->refreshAuthToken();
        if (!$this->token->invalid) {
            try {
                return $this->_post(...func_get_args());
            } catch (YandexDirectInvalidOAuthTokenException) {
            }
        }

        $this->token->makeInvalid();
        throw new BusinessException('Invalid OAuth token for Yandex.Direct source.');
    }

    private function checkAndRefreshAuthToken(): void
    {
        if ($this->token->isExpired()) {
            $this->refreshAuthToken();
        }
    }

    private function refreshAuthToken(): void
    {
        $this->token = app(RefreshYandexAuthTokenAction::class)->execute($this->token);
    }
}

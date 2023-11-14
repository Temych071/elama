<?php

declare(strict_types=1);

namespace Module\Source\Vk\Services;

use App\Exceptions\BusinessException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Module\Source\Sources\Models\SourceAuthToken;
use Module\Source\Vk\Data\AccountData;
use Module\Source\Vk\Data\CampaignData;
use Module\Source\Vk\Data\ClientData;

use function config;
use function url;

final class VkService
{
    private const ERR_CODE_AUTH_FAILED = 5;

    private const VERSION = '5.131';

    private const HOST = 'https://oauth.vk.com';
    private const ENDPOINT_AUTHORIZE = self::HOST . '/authorize';
    private const ENDPOINT_ACCESS_TOKEN = self::HOST . '/access_token';

    public const SCOPE_ADS = 32768;
    public const SCOPE_OFFLINE = 65536;

    private readonly int $clientId;
    private readonly string $clientSecret;
    private readonly string $redirectUrl;

    public function __construct(private readonly ?SourceAuthToken $token = null)
    {
        $this->clientId = (int)config('services.source_vk.client_id');
        $this->clientSecret = config('services.source_vk.client_secret');
        $this->redirectUrl = url(config('services.source_vk.redirect'));
    }

    public function getAuthUrl(
        ?array $scope = [self::SCOPE_ADS, self::SCOPE_OFFLINE],
    ): string {
        $scope_mask = 0;

        foreach ($scope as $scope_setting) {
            $scope_mask |= $scope_setting;
        }

        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'display' => 'page',
            'scope' => $scope_mask,
            'response_type' => 'code',
            'v' => self::VERSION,
        ];

        return self::ENDPOINT_AUTHORIZE . '?' . http_build_query($params);
    }

    public function getAccessToken(string $code)
    {
        $decode_body = [];
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUrl,
            'code' => $code,
        ];

        $response = Http::timeout(15)->get(self::ENDPOINT_ACCESS_TOKEN, $params);

        if ($response->status() !== 200) {
            throw new VkDomainException('Unexpected response status ' . $response->status() . $response->body());
        }

        if (isset($decode_body['error'])) {
            throw new VkDomainException("{$decode_body['error_description']}. OAuth error {$decode_body['error']}");
        }

        return $response->json();
    }

    public function getAccounts(): array
    {
        $response = $this->get('ads.getAccounts');

        return array_map(static fn ($item): \Module\Source\Vk\Data\AccountData => AccountData::from($item), $response ?? []);
    }

    public function getClients(int $accountId): array
    {
        $response = $this->get('ads.getClients', [
            'account_id' => $accountId,
        ]);

        return array_map(static fn ($item): \Module\Source\Vk\Data\ClientData => ClientData::from($item), $response ?? []);
    }

    /**
     * @return array<CampaignData>
     * @throws RequestException
     * @throws VkApiError
     * @throws VkDomainException
     */
    public function getCampaigns(int $accountId, ?int $clientId): array
    {
        $response = $this->getCampaignsRaw($accountId, $clientId);

        return array_map(static fn ($item): \Module\Source\Vk\Data\CampaignData => CampaignData::from($item), $response ?? []);
    }

    /**
     * @param int|null $clientId Обязательно для агентских аккаунтов
     * @throws RequestException
     * @throws VkApiError
     * @throws VkDomainException
     */
    public function getCampaignsRaw(int $accountId, ?int $clientId): array
    {
        return $this->get(
            'ads.getCampaigns',
            array_filter([
                'account_id' => $accountId,
                'client_id' => $clientId,
            ])
        );
    }

    public function getBudget(int $accountId): float
    {
        return (float) $this->get('ads.getBudget', [
            'account_id' => $accountId,
        ]);
    }

    /**
     * @throws RequestException
     * @throws VkApiError
     * @throws VkDomainException
     */
    public function getAdsTargeting(
        int $accountId,
        ?int $clientId,
        array|int $adIds,
    ): array {
        return $this->get(
            'ads.getAdsTargeting',
            array_filter([
                'account_id' => $accountId,
                'client_id' => $clientId,
                'ad_ids' => json_encode(Arr::wrap($adIds), JSON_THROW_ON_ERROR),
            ])
        );
    }

    /**
     * @throws RequestException
     * @throws VkApiError
     * @throws VkDomainException
     */
    public function getTargetGroups(int $accountId, ?int $clientId): array
    {
        return $this->get(
            'ads.getTargetGroups',
            array_filter([
                'account_id' => $accountId,
                'client_id' => $clientId,
            ])
        );
    }

    /**
     * @throws RequestException
     * @throws VkApiError
     * @throws VkDomainException
     * @throws BusinessException
     */
    public function get(string $method, array $params = []): array|string|null
    {
        $response = Http::timeout(15)->get(
            "https://api.vk.com/method/$method",
            array_merge([
                'v' => self::VERSION,
                'access_token' => $this->token->token,
            ], $params)
        )->throw();

        $this->checkHttpStatus($response);

//        if($method === 'ads.getStatistics') {
//            Log::build([
//                'driver' => 'single',
//                'path' => storage_path('logs/vkstat.log'),
//            ])->info('Request body vk stat', [
//                'params' => $params,
//                'data' => $response->body(),
//            ]);
//        }

        $body = mb_convert_encoding($response->body(), 'UTF-8', 'UTF-8');
        $body = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        if (isset($body['error'])) {
            if ($body['error']['error_code'] === self::ERR_CODE_AUTH_FAILED) {
                $this->token->makeInvalid();
                throw new BusinessException('Invalid OAuth token for VK source.');
            }

            throw new VkApiError($body['error']['error_code'] . ' ' . $body['error']['error_msg']);
        }

        return $body['response'] ?? $body;
    }

    private function checkHttpStatus(Response $response): void
    {
        if ($response->status() !== 200) {
            throw new VkDomainException("Invalid http status: {$response->status()}");
        }
    }

    public function execute(string $code): array
    {
        return $this->get('execute', [
            'code' => $code,
        ]);
    }
}

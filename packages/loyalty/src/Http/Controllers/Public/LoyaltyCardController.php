<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Public;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Loyalty\AppleWallet\Services\AppleWalletPassBuilder;
use Loyalty\Models\LoyaltyCard;
use Loyalty\Models\LoyaltyClient;
use Loyalty\Services\Wallet\GoogleWalletService;

final class LoyaltyCardController extends AbstractLoyaltyPublicController
{
    protected string $pageComponent = 'Loyalty/Public/Card';

    protected function getPageData(): array
    {
        return array_merge(parent::getPageData(), [
            'client' => [
                ...$this->getClient()->toArray(),
                'form' => $this->getClient()
                    ->formForLoyalty($this->getLoyalty())
                    ->firstOrFail()
                    ->toArray(),

                'card' => $this->getClient()
                    ->cardForLoyalty($this->getLoyalty())
                    ->first()
                    ?->append(['balance', 'discount'])
                    ?->toArray(),
            ],
        ]);
    }

    public function redirectToGoogleWallet(): string
    {
        /** @var LoyaltyCard $card */
        $card = $this->getClient()->cardForLoyalty($this->getLoyalty())->firstOrFail();

        $service = app(GoogleWalletService::class);

        $service->createWalletCard($card);

        return $service->getWalletCardLink($card);
    }

    public function getAppleWalletPass(): Response
    {
        /** @var LoyaltyCard $card */
        $card = $this->getClient()->cardForLoyalty($this->getLoyalty())->firstOrFail();

        $builder = app(AppleWalletPassBuilder::class);

        return new Response($builder->fromLoyalty($card), 200, [
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/vnd.apple.pkpass',
            'Content-Disposition' => 'attachment; filename="' . 'pass.pkpass' . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Connection' => 'Keep-Alive',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Last-Modified' => gmdate('D, d M Y H:i:s T'),
            'Pragma' => 'public',
        ]);
    }

    protected function getClient(): LoyaltyClient
    {
        /** @var LoyaltyClient $client */
        $client = Request::user('loyalty');

        return $client;
    }
}

<?php

declare(strict_types=1);

namespace Loyalty\Http\Controllers\Public;

use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Loyalty\Http\Requests\LoyaltyFormRequest;
use Loyalty\Models\Loyalty;
use Loyalty\Models\LoyaltyClient;
use Loyalty\Models\LoyaltyForm;

final class LoyaltyFormController extends AbstractLoyaltyPublicController
{
    protected string $pageComponent = 'Loyalty/Public/Registration';

    protected function getPageData(): array
    {
        return array_merge(parent::getPageData(), [
            'freeCardsExists' => $this->getLoyalty()
                ->cards()
                ->whereHas('client', operator: '=', count: 0)
                ->exists(),

            'latestForm' => Request::user('loyalty')
                ->forms()
                ->latest()
                ->first(),
        ]);
    }

    /**
     * @throws ToastException
     */
    public function send(LoyaltyFormRequest $request, Loyalty $publicLoyalty): RedirectResponse
    {
        /** @var LoyaltyClient $client */
        $client = $request->user('loyalty');

        $freeCard = $this->getLoyalty()
            ->cards()
            ->whereHas('client', operator: '=', count: 0)
            ->first();

        if ($freeCard === null) {
            throw new ToastException('На данный момент регистрация недоступна. Повторите попытку позже.');
        }

        $form = new LoyaltyForm();
        $form->client()->associate($client);
        $form->card()->associate($freeCard);
        $form->fill($request->validated());
        $form->save();

        return redirect()->route('loyalty.public.card.show', $publicLoyalty->id);
    }
}

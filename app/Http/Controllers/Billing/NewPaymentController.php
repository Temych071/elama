<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Exceptions\BusinessException;
use App\Exceptions\ToastException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\ArrayShape;
use Module\Billing\Payments\Enums\PaymentMethodStatus;
use Module\Billing\Payments\Models\DiscountCode;
use Module\Billing\Payments\Services\RoboKassaService;
use Module\Billing\Payments\Services\YooKassaService;
use Module\Billing\Subscription\Enums\PlanStatus;
use Module\Billing\Subscription\Enums\SubscriptionStatus;
use Module\Billing\Subscription\Exceptions\SubscriptionAlreadyExistsException;
use Module\Billing\Subscription\Exceptions\SubscriptionNotExistsException;
use Module\Billing\Subscription\Exceptions\SubscriptionsCantReplacePlanToSame;
use Module\Billing\Subscription\Models\Plan;
use Module\Billing\Subscription\Models\Subscription;
use Module\Billing\Subscription\Services\SubscriptionService;
use Module\Campaign\Actions\GetVisitsCountAction;
use Module\Campaign\Models\Campaign;
use Module\User\Models\User;

final class NewPaymentController
{
    private const SESSION_DISCOUNT_CODE_KEY = 'billing:discount_code';

    /**
     * @throws ToastException
     */
    public function __invoke(Request $request, GetVisitsCountAction $getVisitsCountAction): Response
    {
        /** @var User $user */
        $user = $request->user();

        $subs = $user->subscriptions()
            ->whereIn('status', [SubscriptionStatus::active, SubscriptionStatus::paused, SubscriptionStatus::noCharged])
            ->with(['plan', 'campaign'])
            ->get();

        $campaignsVisits = $subs
            ->mapWithKeys(static fn(Subscription $sub): array => [
                (int) $sub->campaign->id => (int) $getVisitsCountAction->execute($sub->campaign),
            ])
            ->toArray();

        return Inertia::render('Billing/Tabs/PaymentTab', [
            'plans' => Plan::query()
                ->where('status', PlanStatus::active)
                ->get(),
            'projects' => $user->ownCampaigns->load([
                'activeSubscription',
                'reviewForms',
            ]),
            'projectVisits' => $campaignsVisits,
            'appliedDiscountCode' => $this
                ->getAppliedDiscountCode()
                ?->only(['code', 'amount', 'percent']),

            'paymentMethods' => $user
                ->paymentMethods
                ->where('status', PaymentMethodStatus::AVAILABLE),
            'autoRefillSettings' => $user->auto_refill_settings,
        ]);
    }

    /**
     * @throws BusinessException
     */
    public function applyDiscountCode(Request $request): RedirectResponse
    {
        $code = $request->validate([
            'discount_code' => ['required', 'string', 'max:32'],
        ])['discount_code'];

        /** @var DiscountCode $discountCode */
        $discountCode = DiscountCode::query()
            ->where('code', $code)
            ->available()
            ->first();

        if (is_null($discountCode)) {
            throw new ToastException('Указанный промокод недействителен.');
        }

        Session::put(self::SESSION_DISCOUNT_CODE_KEY, $discountCode->id);
        Session::save();

        return redirect()->back()->with('toast', [
            'type' => 'info',
            'message' => "Промокод `$code` применён.",
        ]);
    }

    /**
     * @return array{confirmation_url: string}
     * @throws BusinessException
     */
    #[ArrayShape(['confirmation_url' => "string"])]
    public function create(Request $request): array
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'integer'],
            'savePaymentMethod' => ['nullable', 'boolean'],
        ]);

        $discountCodeId = $this->getAppliedDiscountCode()?->id;

        /** @var User $user */
        $user = $request->user();

        $service = new RoboKassaService();

        $confirmUrl = $service->paymentCreateUrl(
            user: $user,
            amount: (int) $data['amount'],
            saveMethod: (bool) ($data['savePaymentMethod'] ?? false),
//            saveMethod: true,
            metadata: !is_null($discountCodeId) ? ['discount_code' => $discountCodeId] : [],
        );

        self::clearDiscountCode();

        return [
            'confirmation_url' => $confirmUrl,
        ];
    }

    /**
     * @throws ValidationException
     * @throws ToastException
     */
    public function createInvoice(Request $request): RedirectResponse
    {
        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\D/', '', (string) $data['phone']);
        }

        $data = Validator::validate($data, [
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['required', 'string', 'email'],
            'company_name' => ['required', 'string'],
            'formatted_amount' => ['required', 'numeric', 'min:1'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $user->invoices()->create([...$data, 'discount_code_id' => $this->getAppliedDiscountCode()?->id]);

        self::clearDiscountCode();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Счёт выставлен. Средстав будут зачислены после подтверждения администратором.',
        ]);
    }

    private static function clearDiscountCode(): void
    {
        Session::forget(self::SESSION_DISCOUNT_CODE_KEY);
        Session::save();
    }

    /**
     * @throws ToastException
     */
    private function getAppliedDiscountCode(): ?DiscountCode
    {
        $discountCodeId = Session::get(self::SESSION_DISCOUNT_CODE_KEY);
        if (empty($discountCodeId)) {
            return null;
        }

        $discountCode = DiscountCode::query()
            ->where('id', $discountCodeId)
            ->available()
            ->first();

        if ($discountCode === null) {
            self::clearDiscountCode();
            throw new ToastException('Указанный промокод недействителен.');
        }

        return $discountCode;
    }

    /**
     * @throws \Throwable
     * @throws SubscriptionAlreadyExistsException
     * @throws SubscriptionNotExistsException
     * @throws ToastException
     */
    public function updateSubscription(Request $request): RedirectResponse
    {
        $request->validate([
            'project_id' => ['required', 'exists:campaigns,id'],
            'plan_id' => ['required', 'exists:billing_plans,id'],
        ]);

        $project = Campaign::query()->findOrFail($request->input('project_id'));
        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($request->input('plan_id'));
        /** @var User $user */
        $user = $request->user();

        try {
            app(SubscriptionService::class)->replace($project, $user, $plan);
        } catch (SubscriptionsCantReplacePlanToSame) {
            throw new ToastException('Нельзя сменить тариф на такой же.');
        }

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Тарифный план успешно изменён.',
        ]);
    }
}

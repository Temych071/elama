<?php

declare(strict_types=1);

namespace SocialWidget\Http\Controllers\Callback;

use App\Exceptions\ToastException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use SocialWidget\Crm\Impl\AmoService;
use SocialWidget\Models\SocialWidget;

final class AmoCrmAuthCallbackController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
            'state' => ['required', 'string'],
            'referer' => ['required', 'string'],
        ]);

        /** @var SocialWidget $widget */
        $widget = SocialWidget::query()
            ->where('id', $data['state'])
            ->whereIn('project_id', $request->user()->campaigns()->pluck('campaigns.id'))
            ->firstOrFail();

        $service = new AmoService(domain: $data['referer']);

        try {
            $widget->amo_access_token = $service->getAccessToken($data['code']);
            $widget->amo_domain = $data['referer'];
            $widget->save();
        } catch (RequestException) {
            throw new ToastException(
                'При установке интеграции возникли ошибки...',
                route('social-widget.private.settings.integrations', [$widget->project_id, $widget])
            );
        }

        return redirect()
            ->route('social-widget.private.settings.integrations', [$widget->project_id, $widget])
            ->with('toast', [
                'type' => 'success',
                'message' => 'Интеграция с amoCrm подключена.',
            ]);
    }

}

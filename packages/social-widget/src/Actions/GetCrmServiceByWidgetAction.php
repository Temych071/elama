<?php

declare(strict_types=1);

namespace SocialWidget\Actions;

use Illuminate\Support\Facades\Log;
use SocialWidget\Crm\CrmRequestAuthException;
use SocialWidget\Crm\CrmService;
use SocialWidget\Crm\Impl\AmoService;
use SocialWidget\Crm\Impl\BitrixService;
use SocialWidget\Models\SocialWidget;
use Throwable;

final class GetCrmServiceByWidgetAction
{
    public function execute(SocialWidget $widget): ?CrmService
    {
        $settings = $widget->crm_integrations_settings;
        if ($settings->bx_enabled) {
            return new BitrixService($widget);
        }

        if ($settings->amo_enabled && $widget->amo_domain && $widget->amo_access_token) {
            return new AmoService($widget->amo_domain, $widget);
        }

        return null;
    }
}

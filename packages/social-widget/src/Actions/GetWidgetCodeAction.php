<?php

declare(strict_types=1);

namespace SocialWidget\Actions;

use SocialWidget\Models\SocialWidget;

final class GetWidgetCodeAction
{
    public function execute(SocialWidget $widget): string
    {
        $initScriptUrl = url('/social-widget/init.js');
        $apiUrl = url('/sw/api/v1');
        return <<<END
        <script type="text/javascript">
            window.dgSocialWidgetData = {
                widgetId: '$widget->id',
                apiUrl: '$apiUrl',
            };
        </script>
        <script type="text/javascript" src="$initScriptUrl" defer></script>
        END;
    }
}

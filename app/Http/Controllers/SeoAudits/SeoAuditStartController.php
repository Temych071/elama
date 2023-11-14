<?php

declare(strict_types=1);

namespace App\Http\Controllers\SeoAudits;

use Illuminate\Http\Request;
use Module\LinkChecker\Actions\StartSeoAuditAction;

final class SeoAuditStartController
{
    public function __invoke(Request $request, StartSeoAuditAction $startSeoAudit)
    {
        if (!preg_match('/^(http(s|):\/\/)/i', (string) $request['url'])) {
            $request['url'] = 'http://' . $request['url'];
        }

        $url = $request->validate([
            'url' => ['required', 'url'],
        ])['url'];


        return $startSeoAudit->execute(
            link: $url,
            user: $request->user(),
        );
    }
}

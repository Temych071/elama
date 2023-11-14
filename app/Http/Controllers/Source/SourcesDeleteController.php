<?php

declare(strict_types=1);

namespace App\Http\Controllers\Source;

use App\Exceptions\BusinessException;
use Illuminate\Http\RedirectResponse;
use Module\Campaign\Models\Campaign;
use Module\Source\Sources\Models\Source;
use Throwable;

final class SourcesDeleteController
{
    /**
     * @throws BusinessException
     * @throws Throwable
     */
    public function __invoke(Campaign $campaign, string $source_type): RedirectResponse
    {
        /** @var Source $source */
        $source = $campaign
            ->sources()
            ->where('settings_type', $source_type)
            ->first();

        if (!$source) {
            throw new BusinessException("Source `$source_type` not found.");
        }

        $source->deleteOrFail();

        return redirect()
            ->route('campaign.source', $campaign)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Источник успешно удалён.',
            ]);
    }
}

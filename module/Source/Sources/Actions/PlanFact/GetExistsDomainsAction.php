<?php

declare(strict_types=1);

namespace Module\Source\Sources\Actions\PlanFact;

use App\Exceptions\BusinessException;
use Module\Source\Sources\Models\Source;

final class GetExistsDomainsAction
{
    /**
     * @throws BusinessException
     */
    public function execute(Source $source, string $visit_source): array
    {
        return match ($source->settings_type) {
            Source::TYPE_GOOGLE_ANALYTICS =>
            app(\Module\Source\GoogleAnalytics\Actions\PlanFact\GetExistsDomainsAction::class)
                ->execute($source, $visit_source),

            Source::TYPE_YANDEX_METRIKA =>
            app(\Module\Source\YandexMetrika\Actions\PlanFact\GetExistsDomainsAction::class)
                ->execute($source, $visit_source),

            default =>
            throw new BusinessException('Stats source not connected.'),
        };
    }
}

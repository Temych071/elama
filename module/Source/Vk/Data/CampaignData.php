<?php

declare(strict_types=1);

namespace Module\Source\Vk\Data;

use Spatie\LaravelData\Data;

final class CampaignData extends Data
{
    public const STATUS_STOPPED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETED = 2;


    /**
     * Обычная кампания, в которой можно создавать любые объявления, кроме описанных в следующих пунктах
     */
    public const TYPE_NORMAL = 'normal';

    /**
     * Кампания, в которой можно рекламировать только администрируемые Вами приложения и у которой есть отдельный бюджет
     */
    public const TYPE_VK_APPS_MANAGED = 'vk_apps_managed';

    /**
     * Кампания, в которой можно рекламировать только мобильные приложения
     */
    public const TYPE_MOBILE_APPS = 'mobile_apps';

    /**
     * Кампания, в которой можно рекламировать только записи в сообществе
     */
    public const TYPE_PROMOTED_POSTS = 'promoted_posts';

    /**
     * Кампания, в которой можно рекламировать только объявления адаптивного формата
     */
    public const TYPE_ADAPTIVE_ADS = 'adaptive_ads';

    public function __construct(
        public readonly int $all_limit,
        public readonly int $day_limit,
        public readonly int $id,
        public readonly string $name,
        public readonly int $status,
        public readonly string $type,
        public readonly int $start_time,
        public readonly int $create_time,
        public readonly int $update_time,
    ) {
    }
}

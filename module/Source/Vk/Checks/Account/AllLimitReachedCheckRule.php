<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Account;

use Illuminate\Support\Facades\DB;
use Module\Source\Vk\Models\VkCampaignParam;
use Module\Source\Vk\Models\VkSettings;

final class AllLimitReachedCheckRule extends VkAccountCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'all-limit-reached.title';
    protected string $desc = 'all-limit-reached.desc';
    protected string $message = 'all-limit-reached.message';

    /**
     * @param  VkSettings  $object
     */
    public function check($object): bool
    {
        $limit = $object->client->all_limit;
        $res = DB::table('vk_ads_statistics')
            ->where('settings_id', $object->id)
            ->selectRaw('SUM(spent) AS all_spent, SUM(spent)/SUM(clicks) AS avg_cost')
            ->havingNotNull('avg_cost')
            ->first();

        if (is_null($res)) {
            return true;
        }

        // TODO: Вообще всё сложно))

        return (
            ($res->all_spent + $res->avg_cost) < $limit
            && !is_null($object->vkCampaigns->firstWhere('status', VkCampaignParam::STATUS_ACTIVE))
        );
    }

    /**
     * @param  VkSettings  $object
     */
    public function canApplyRule($object): bool
    {
        return !is_null($object->client);
    }
}

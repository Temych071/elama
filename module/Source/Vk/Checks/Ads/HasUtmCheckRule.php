<?php

declare(strict_types=1);

namespace Module\Source\Vk\Checks\Ads;

use Illuminate\Support\Str;
use Module\Source\Vk\Models\VkAdParam;

final class HasUtmCheckRule extends VkAdCheckRule
{
    protected bool $textFromLang = true;
    protected string $title = 'has-utm.title';
    protected string $desc = 'has-utm.desc';
    protected string $message = 'has-utm.message';

    private const NEEDED_UTMS = [
        'utm_source',
        'utm_campaign',
        'utm_medium',
    ];

    /**
     * @param  VkAdParam  $object
     */
    public function check($object): bool
    {
        $query = explode('&', Str::after($object->link_url, '?'));
        $params = [];
        foreach ($query as $param) {
            $i = explode('=', $param);
            $params[$i[0]] = $i[1] ?? null;
        }

        foreach (self::NEEDED_UTMS as $utm) {
            if (empty($params[$utm])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  VkAdParam  $object
     */
    public function canApplyRule($object): bool
    {
        return Str::betweenFirst($object->link_url, '://', '/') !== 'vk.com';
    }
}

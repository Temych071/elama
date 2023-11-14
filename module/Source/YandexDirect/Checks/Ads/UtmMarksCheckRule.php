<?php

declare(strict_types=1);

namespace Module\Source\YandexDirect\Checks\Ads;

use Illuminate\Support\Str;

final class UtmMarksCheckRule extends YandexDirectAdCheckRule
{
    private const MUST_EXISTS_UTMS = [
        'utm_source',
        'utm_campaign',
    ];

    protected bool $textFromLang = true;
    protected string $title = 'utm-marks.title';
    protected string $desc = 'utm-marks.desc';
    protected string $message = 'utm-marks.message';

    public function check($object): bool
    {
        $paramsString = explode('&', Str::between($object->href, '?', '#'));

        $params = [];
        foreach ($paramsString as $param) {
            $p = explode('=', $param);
            if (count($p) > 1) {
                $params[$p[0]] = $p[1];
            }
        }

        foreach (self::MUST_EXISTS_UTMS as $utm) {
            if (empty($params[$utm])) {
                return false;
            }
        }

        return true;
    }
}

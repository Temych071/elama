<?php

namespace Module\Source\YandexDirect\Actions;

use Illuminate\Support\Str;
use Module\Source\Sources\Exceptions\UnsupportedSourceTypeException;
use Module\Source\Sources\Models\Source;
use Module\Source\YandexDirect\Actions\Fetch\GetSiteLinksAction;
use Module\Source\YandexDirect\Models\DirectAd;

class CheckSitelinksAction
{
    private const MUST_EXISTS_UTMS = [
        'utm_source',
        'utm_campaign',
    ];

    /**
     * @throws UnsupportedSourceTypeException
     */
    public function execute(Source $source): void
    {
        $sitelinksSets = app(GetSiteLinksAction::class)->execute($source);

        $res = [];
        foreach ($sitelinksSets as $sitelinksSet) {
            $key = (int)$sitelinksSet['AdId'];
            if (empty($res[$key])) {
                $res[$key] = [
                    'utms' => 0,
                    'desc' => 0,
                ];
            }

            foreach ($sitelinksSet['Sitelinks'] as $sitelink) {
                if (!self::checkUtms($sitelink['Href'])) {
                    $res[$key]['utms']++;
                }
                if (empty($sitelink['Description'])) {
                    $res[$key]['desc']++;
                }
            }
        }

        $source->settings->directAdsSel->each(static function (DirectAd $directAd) use ($res): void {
            $key = (int)$directAd->id;
            if (!empty($res[$key])) {
                $directAd->sitelink_utms = $res[$key]['utms'];
                $directAd->sitelink_desc = $res[$key]['desc'];

                $directAd->save();
            }
        });
    }

    private static function checkUtms(string $url): bool
    {
        $paramsString = explode('&', Str::between($url, '?', '#'));

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

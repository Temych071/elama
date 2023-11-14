<?php

declare(strict_types=1);

namespace Module\DgMarks\Services;

use Illuminate\Support\Str;
use Module\DgMarks\DTO\DgMarks;

final class DgMarksParser
{
    public function parseFromStr(string $str): DgMarks
    {
        if (empty($str)) {
            return new DgMarks();
        }

        return DgMarks::fromArray(explode('_', $str));
    }

    public function parseFromUrl(string $url): DgMarks
    {
        return $this->parseFromStr((string)(self::parseQueryParamsFromUrl($url)['dg'] ?? ''));
    }

    /**
     * @return array<string, string>
     */
    private static function parseQueryParamsFromUrl(string $url): array
    {
//        $queryString = parse_url($url, PHP_URL_QUERY)['query'];
        $queryString = Str::betweenFirst($url, '?', '#');
        $queryStringParams = explode('&', $queryString);

        $res = [];
        foreach ($queryStringParams as $stringParam) {
            $param = explode('=', $stringParam);
            $res[$param[0]] = isset($param[1]) ? $param[1] : true;
        }

        return $res;
    }
}

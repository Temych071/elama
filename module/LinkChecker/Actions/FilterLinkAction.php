<?php

declare(strict_types=1);

namespace Module\LinkChecker\Actions;

final class FilterLinkAction
{
    public function execute(string $url): string
    {
        $parsed = parse_url($url);
        $query = $this->filterQueryParams($parsed);
        $scheme = $parsed['scheme'] ?? 'https';
        $path = $parsed['path'] ?? '';

        return $scheme . '://' . $parsed['host'] . $path . $query;
    }

    private function filterQueryParams(bool|array|int|string|null $parsed): string
    {
        $query = '';

        if (isset($parsed['query'])) {
            parse_str((string) $parsed['query'], $params);

            $params = array_filter($params, static function ($key): bool {
                $stopWords = [
                    'utm_',
                    'roistat',
                    '_openstat',
                    'yclid',
                    'pm_',
                    'cm_',
                    'rs',
                    'block',
                    'device',
                    'region',
                    'region_name',
                    'retargeting',
                    'source',
                    'position',
                    'placement',
                    'network',
                ];

                foreach ($stopWords as $word) {
                    if (str_starts_with($key, $word)) {
                        return false;
                    }
                }

                return true;
            }, ARRAY_FILTER_USE_KEY);

            $query = http_build_query($params);

            if (!empty($query)) {
                $query = '?' . $query;
            }
        }

        return $query;
    }
}

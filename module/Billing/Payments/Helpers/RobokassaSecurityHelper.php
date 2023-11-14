<?php

namespace Module\Billing\Payments\Helpers;

use RuntimeException;

final class RobokassaSecurityHelper
{
    /**
     * Проверяет формат IP адреса и вызывает соответствующие методы для проверки среди IPv4 адресов RoboKassa
     *
     * @param $ip  - IPv4 адрес webhook уведомления
     *
     * @throws RuntimeException - исключение будет выброшено, если не удастся установить формат IP адреса
     */
    public function isIPTrusted($ip): bool
    {
        if (!$this->isIPv4($ip)) {
            return $this->checkInIPv4TrustedList($ip);
        }
        throw new RuntimeException(
            'Could not recognize IPv4: ' . $ip
        );
    }

    /**
     * Проверяет, является ли переданное в функцию значение IPv4 адресом
     *
     * @param $ip  - IP адрес
     * @return bool - true - является, false - не является
     */
    private function isIPv4($ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Проверяет IPv4 адрес в списке IPv4 адресов Юkassa
     *
     * @param $ip  - IPv4 адрес
     */
    private function checkInIPv4TrustedList($ip): bool
    {
        foreach ($this->getIPv4TrustedList() as $range) {
            if ($this->isIPInV4Range($ip, $range)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Осуществляет проверку, входит ли IPv4 адрес $ip в диапазон IPv4 адресов $range
     *
     * @param $ip  - IPv4 адрес
     * @param $range  - IPv4 адрес, или диапазон IPv4 адресов в формате CIDR
     */
    private function isIPInV4Range($ip, string $range): bool
    {
        $ip_dec = ip2long($ip);

        if (!str_contains((string) $range, '/')) {
            return ip2long($ip) === ip2long($range);
        }

        [$range, $netmask] = explode('/', (string) $range, 2);
        [$a, $b, $c, $d] = explode('.', $range);

        $range = sprintf("%u.%u.%u.%u", $a, $b, $c, $d);
        $range_dec = ip2long($range);

        $wildcard_dec = (2 ** (32 - $netmask)) - 1;
        $netmask_dec = ~$wildcard_dec;

        return (($ip_dec & $netmask_dec) === ($range_dec & $netmask_dec));
    }

    /**
     * Возвращает список диапазонов IPv4 адресов в формате CIDR и отдельных IPv4 адресов
     * с которых RoboKassa может отправлять уведомления
     *
     * @return string[]
     */
    private function getIPv4TrustedList(): array
    {
        return ['185.59.216.0/24'];
    }
}

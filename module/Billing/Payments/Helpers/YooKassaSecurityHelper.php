<?php

namespace Module\Billing\Payments\Helpers;

use RuntimeException;

final class YooKassaSecurityHelper
{
    /**
     * Проверяет формат IP адреса и вызывает соответствующие методы для проверки среди IPv4 и IPv6 адресов Юkassa
     *
     * @param $ip  - IPv4 или IPv6 адрес webhook уведомления
     *
     * @throws RuntimeException - исключение будет выброшено, если не удастся установить формат IP адреса
     */
    public function isIPTrusted($ip): bool
    {
        if (!$this->isIPv6($ip)) {
            return $this->checkInIPv4TrustedList($ip);
        }

        if (!$this->isIPv4($ip)) {
            return $this->checkInIPv6TrustedList($ip);
        }
        throw new RuntimeException(
            'Could not recognize IPv4 or IPv6: ' . $ip
        );
    }

    /**
     * Проверяет, является ли переданное в функцию значение IPv6 адресом
     *
     * @param $ip  - IP адрес
     * @return bool - true - является, false - не является
     */
    private function isIPv6($ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
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
     * Проверяет IPv6 адрес в списке IPv6 адресов Юkassa
     *
     * @param $ip  - IPv6 адрес
     */
    private function checkInIPv6TrustedList($ip): bool
    {
        foreach ($this->getIPv6TrustedList() as $range) {
            if ($this->isIPInV6Range($ip, $range)) {
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
     * Осуществляет проверку, входит ли IPv6 адрес $ip в диапазон IPv6 адресов $range
     *
     * @param $ip
     * @param $range
     */
    private function isIPInV6Range($ip, array $range): bool
    {
        $firstInRange = inet_pton($range[0]);
        $lastInRange = inet_pton($range[1]);

        $ip = inet_pton($ip);

        return (strlen($ip) === strlen($firstInRange))
            && ($ip >= $firstInRange && $ip <= $lastInRange);
    }

    /**
     * Возвращает список диапазонов IPv4 адресов в формате CIDR и отдельных IPv4 адресов
     * с которых Юkassa может отправлять уведомления
     *
     * @return string[]
     */
    private function getIPv4TrustedList(): array
    {
        return ['185.71.76.0/27', '185.71.77.0/27', '77.75.153.0/25', '77.75.154.128/25', '77.75.156.11', '77.75.156.35'];
    }

    /**
     * Возвращает список диапазонов IPv6 адресов с которых Юkassa может отправлять уведомления
     *
     * @return string[][]
     */
    private function getIPv6TrustedList(): array
    {
        return [['2a02:5180:0000:1509:0000:0000:0000:0000', '2a02:5180:0000:1509:ffff:ffff:ffff:ffff'], ['2a02:5180:0000:2655:0000:0000:0000:0000', '2a02:5180:0000:2655:ffff:ffff:ffff:ffff'], ['2a02:5180:0000:1533:0000:0000:0000:0000', '2a02:5180:0000:1533:ffff:ffff:ffff:ffff'], ['2a02:5180:0000:2669:0000:0000:0000:0000', '2a02:5180:0000:2669:ffff:ffff:ffff:ffff']];
    }
}

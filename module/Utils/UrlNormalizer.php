<?php

declare(strict_types=1);

namespace Module\Utils;

/**
 * Syntax based normalization of URI's
 *
 * This normalises URI's based on the specification RFC 3986
 * https://tools.ietf.org/html/rfc3986
 *
 * Example usage:
 * <code>
 * require_once 'vendor/autoload.php';
 *
 * $url = 'eXAMPLE://a/./b/../b/%63/%7bfoo%7d';
 * $un = new URL\Normalizer( $url );
 * echo $un->normalize();
 *
 * // result: "example://a/b/c/%7Bfoo%7D"
 * </code>
 *
 * @author Glen Scott <glen@glenscott.co.uk>
 */
class UrlNormalizer
{
    private string $url;
    private ?string $scheme = null;
    private $host;
    private $port;
    private $path;
    private $query;
    private $fragment;
    private array $default_scheme_ports = ['http:' => 80, 'https:' => 443,];
    private array $components = ['scheme', 'host', 'port', 'pass', 'path', 'query', 'fragment',];

    /**
     * Does the original URL have a ? query delimiter
     */
    private bool $query_delimiter;

    public function __construct(string $url, private $remove_empty_delimiters, private $sort_query_params)
    {
        if ($url !== '' && $url !== '0') {
            $this->setUrl($url);
        }
    }

    public static function make(string $url, $removeEmptyDelimiters = true, $sortQueryParams = true): UrlNormalizer
    {
        return new self($url, $removeEmptyDelimiters, $sortQueryParams);
    }

    private function getQuery(array $query): array
    {
        $qs = [];
        foreach ($query as $qk => $qv) {
            $qs[rawurldecode((string) $qk)] = is_array($qv) ? $this->getQuery($qv) : rawurldecode((string) $qv);
        }
        return $qs;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): bool
    {
        $this->url = $url;

        $this->query_delimiter = str_contains($this->url, '?');

        // parse URL into respective parts
        $url_components = $this->mbParseUrl($this->url);

        if (!$url_components) {
            // Reset URL
            $this->url = '';

            // Flush properties
            foreach ($this->components as $key) {
                if (property_exists($this, $key)) {
                    $this->$key = '';
                }
            }

            return false;
        }

        // Update properties
        foreach ($url_components as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        // Flush missing components
        $missing_components = array_diff(
            array_values($this->components),
            array_keys($url_components)
        );

        foreach ($missing_components as $key) {
            if (property_exists($this, $key)) {
                $this->$key = '';
            }
        }

        return true;
    }

    public function normalize(): string
    {
        // URI Syntax Components
        // scheme authority path query fragment
        // @link https://tools.ietf.org/html/rfc3986#section-3

        // Scheme
        // @link https://tools.ietf.org/html/rfc3986#section-3.1

        if ($this->scheme) {
            // Converting the scheme to lower case
            $this->scheme = strtolower($this->scheme) . ':';
        }

        // Authority
        // @link https://tools.ietf.org/html/rfc3986#section-3.2

        $authority = '';
        if ($this->host) {
            $authority .= '//';

            // Host
            // @link https://tools.ietf.org/html/rfc3986#section-3.2.2

            // Converting the host to lower case
            if (mb_detect_encoding((string) $this->host) === 'UTF-8') {
                $authority .= mb_strtolower((string) $this->host, 'UTF-8');
            } else {
                $authority .= strtolower((string) $this->host);
            }

            // Port
            // @link https://tools.ietf.org/html/rfc3986#section-3.2.3

            // Removing the default port
            if (isset($this->default_scheme_ports[$this->scheme])
                && $this->port == $this->default_scheme_ports[$this->scheme]) {
                $this->port = '';
            }

            if ($this->port) {
                $authority .= ':' . $this->port;
            }
        }

        // Path
        // @link https://tools.ietf.org/html/rfc3986#section-3.3

        if ($this->path) {
            $this->path = $this->removeAdditionalPathPrefixSlashes($this->path);
            $this->path = $this->removeDotSegments($this->path);
            $this->path = $this->urlDecodeUnreservedChars($this->path);
            $this->path = $this->urlDecodeReservedSubDelimChars($this->path);
        } elseif ($this->url !== '' && $this->url !== '0') {
            // Add default path only when valid URL is present
            // Adding trailing /
            $this->path = '/';
        }

        // Query
        // @link https://tools.ietf.org/html/rfc3986#section-3.4

        if ($this->query) {
            $query = $this->parseStr($this->query);

            //encodes every parameter correctly
            $qs = $this->getQuery($query);

            $this->query = '?';

            if ($this->sort_query_params) {
                ksort($qs);
            }

            foreach ($qs as $key => $val) {
                if (strlen($this->query) > 1) {
                    $this->query .= '&';
                }

                if (is_array($val)) {
                    foreach ($val as $i => $iValue) {
                        if ($i > 0) {
                            $this->query .= '&';
                        }
                        $this->query .= rawurlencode($key) . '=' . rawurlencode((string) $iValue);
                    }
                } else {
                    $this->query .= rawurlencode($key) . '=' . rawurlencode((string) $val);
                }
            }

            // Fix http_build_query adding equals sign to empty keys
            $this->query = str_replace('=&', '&', rtrim($this->query, '='));
        } elseif ($this->query_delimiter && !$this->remove_empty_delimiters) {
            $this->query = '?';
        }

        // Fragment
        // @link https://tools.ietf.org/html/rfc3986#section-3.5

        if ($this->fragment) {
            $this->fragment = rawurldecode((string) $this->fragment);
            $this->fragment = rawurlencode($this->fragment);
            $this->fragment = '#' . $this->fragment;
        }

        $this->setUrl($this->scheme . $authority . $this->path . $this->query . $this->fragment);

        return $this->getUrl();
    }

    /**
     * Path segment normalization
     * https://tools.ietf.org/html/rfc3986#section-5.2.4
     */
    public function removeDotSegments($path)
    {
        $new_path = '';

        while (!empty($path)) {
            // A
            $pattern_a = '!^(\.\./|\./)!x';
            $pattern_b_1 = '!^(/\./)!x';
            $pattern_b_2 = '!^(/\.)$!x';
            $pattern_c = '!^(/\.\./|/\.\.)!x';
            $pattern_d = '!^(\.|\.\.)$!x';
            $pattern_e = '!(/*[^/]*)!x';

            if (preg_match($pattern_a, (string) $path)) {
                // remove prefix from $path
                $path = preg_replace($pattern_a, '', (string) $path);
            } elseif (preg_match($pattern_b_1, (string) $path, $matches) || preg_match($pattern_b_2, (string) $path, $matches)) {
                $path = preg_replace("!^" . $matches[1] . "!", '/', (string) $path);
            } elseif (preg_match($pattern_c, (string) $path, $matches)) {
                $path = preg_replace('!^' . preg_quote($matches[1], '!') . '!x', '/', (string) $path);

                // remove the last segment and its preceding "/" (if any) from output buffer
                $new_path = preg_replace('!/([^/]+)$!x', '', $new_path);
            } elseif (preg_match($pattern_d, (string) $path)) {
                $path = preg_replace($pattern_d, '', (string) $path);
            } elseif (preg_match($pattern_e, (string) $path, $matches)) {
                $first_path_segment = $matches[1];

                $path = preg_replace('/^' . preg_quote($first_path_segment, '/') . '/', '', (string) $path, 1);

                $new_path .= $first_path_segment;
            }
        }

        return $new_path;
    }

    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * Decode unreserved characters
     *
     * @link https://tools.ietf.org/html/rfc3986#section-2.3
     */
    public function urlDecodeUnreservedChars($string): string
    {
        $string = rawurldecode((string) $string);
        $string = rawurlencode($string);

        return str_replace(['%2F', '%3A', '%40'], ['/', ':', '@'], $string);
    }

    /**
     * Decode reserved sub-delims
     *
     * @link https://tools.ietf.org/html/rfc3986#section-2.2
     */
    public function urlDecodeReservedSubDelimChars($string): string
    {
        return str_replace(
            ['%21', '%24', '%26', '%27', '%28', '%29', '%2A', '%2B', '%2C', '%3B', '%3D'],
            ['!', '$', '&', "'", '(', ')', '*', '+', ',', ';', '='],
            (string) $string
        );
    }

    /**
     * Replacement for PHP's parse_string which does not deal with spaces or dots in key names
     *
     * @param string $string URL query string
     * @return array key value pairs
     */
    private function parseStr(string $string): array
    {
        $params = [];

        $pairs = explode('&', $string);

        foreach ($pairs as $pair) {
            if ($pair === '' || $pair === '0') {
                continue;
            }

            $var = explode('=', $pair, 2);
            $val = $var[1] ?? '';

            if (isset($params[$var[0]])) {
                if (is_array($params[$var[0]])) {
                    $params[$var[0]][] = $val;
                } else {
                    $params[$var[0]] = [$params[$var[0]], $val];
                }
            } else {
                $params[$var[0]] = $val;
            }
        }

        return $params;
    }

    private function mbParseUrl(string $url): bool|array
    {
        $result = [];

        // Build arrays of values we need to decode before parsing
        $entities = ['%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%24', '%2C', '%2F', '%3F', '%23', '%5B', '%5D'];
        $replacements = ['!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "$", ",", "/", "?", "#", "[", "]"];

        // Create encoded URL with special URL characters decoded so it can be parsed
        // All other characters will be encoded
        $encodedURL = str_replace($entities, $replacements, urlencode((string) $url));

        // Parse the encoded URL
        $encodedParts = parse_url($encodedURL);

        // Now, decode each value of the resulting array
        if ($encodedParts) {
            foreach ($encodedParts as $key => $value) {
                $result[$key] = urldecode(str_replace($replacements, $entities, $value));
            }
            return $result;
        }

        return false;
    }

    /*
     * Converts ////foo to /foo within each path segment
     */
    private function removeAdditionalPathPrefixSlashes($path): string
    {
        return preg_replace('/(\/)+/', '/', (string) $path);
    }
}

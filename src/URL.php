<?php

namespace Codecrafted\UrlExtractor;

/**
 * wrok with url
 */
class URL
{

    /**
     * all urls
     *
     * @var array
     */
    protected $urls = '';

    /**
     * find all urls insinde your address with your count limit of each
     *
     * @param string|array $addresses
     * @param integer $limit
     * @return object
     */
    public function extractURL(string|array $addresses, int $limit = 0)
    {

        if (is_string($addresses)) {
            $links = $this->findURL($addresses, $limit);
        } elseif (is_array($addresses)) {
            foreach ($addresses as $address) {
                $links[] = $this->findURL($address, $limit);
            }
        }

        $this->urls = $links;

        return $this;
    }


    /**
     * only use in extractURL() function for searching urls
     *
     * @param string $address
     * @param integer $limit
     * @return array
     */
    private function findURL(string $address, int $limit): array
    {
        $html = file_get_contents(trim($address));

        $urls = [];
        $start = 0;

        do {

            $position_https = strpos($html, 'https://', $start);
            $position_http = strpos($html, 'http://', $start);

            if ($position_https === false) {
                if ($position_http !== false) {
                    $start_position = $position_http;
                } else {
                    continue;
                }
            } elseif ($position_http === false) {
                $start_position = $position_https;
            } else {
                if ($position_https <= $position_http) {
                    $start_position = $position_https;
                } else {
                    $start_position = $position_http;
                }
            }

            $end_position = strpos($html, '"', $start_position);
            $link = substr($html, $start_position, ($end_position - $start_position)) . "\r\n";
            $link = str_replace("\r", "", $link);
            $link = str_replace("\n", "", $link);
            $link = trim($link);
            $link = trim($link, '/');
            $urls[] = $link;
            $start = $end_position;
            $urls = array_unique($urls);
        } while ((strpos($html, 'http://', $start) !== false || strpos($html, 'https://', $start) !== false) && count($urls) !== $limit);

        return $urls;
    }

    /**
     * It apply limit count to object
     *
     * @param integer $count
     * @return object
     */
    public function limit(int $count): object
    {

        $new = [];
        foreach ($this->urls as $url) {
            if (is_array($url)) {
                $new = array_merge($new, $url);
            } else {
                $new[] = $url;
            }
        }

        $this->urls =
            array_slice($new, 0, $count);

        return $this;
    }

    /**
     * return all links as a array
     *
     * @return array
     */
    public function getURL(): array
    {
        return $this->urls;
    }

    /**
     * return all links as string
     *
     * @return string
     */
    public function showURL(): string
    {
        $links = '';
        foreach ($this->urls as $url) {

            if (is_array($url)) {
                foreach ($url as $value) {
                    $links .= urldecode($value) . "\n";
                }
            } else {
                $links .=  urldecode($url) . "\n";
            }
        }

        return $links;
    }

    /**
     * show links in browser as html
     *
     * @return void
     */
    public function showAsHTML()
    {
        echo '<pre>';
        foreach ($this->urls as $url) {

            if (is_array($url)) {
                foreach ($url as $value) {
                    echo urldecode($value) . "\n";
                }
            } else {
                echo urldecode($url) . "\n";
            }
        }
        echo '</pre>';
    }
}

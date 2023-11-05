<?php

namespace Codecrafted\UrlExtractor;

require_once './vendor/codecrafted/iron-elephant/src/heart.php';


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

            $position_collection = $this->serachNext($html, $start);

            $filtered_positions = array_filter($position_collection, function ($value) {
                return $value !== false;
            });

            if (empty($filtered_positions)) {
                break;
            }

            $start_position = min(array_values($filtered_positions));

            $finding_method = array_search($start_position, $filtered_positions);

            if ($finding_method === 'https://' || $finding_method === 'http://') {
                $char = null;
                $i = 1;
                do {
                    $char = $html[$start_position - $i];
                    $i++;
                } while ($char !== "'" && $char !== '"');
                $end_position = strpos($html, $char, $start_position);
                $link = substr($html, $start_position, ($end_position - $start_position));
            } else {
                $char = null;
                $i = 0;
                do {
                    $char = $html[$start_position + strlen($finding_method) + $i];
                    $i++;
                } while ($char !== "'" && $char !== '"');
                $start_position += strlen($finding_method) + $i;
                $end_position = strpos($html, $char, $start_position);
                $link = substr($html, $start_position, ($end_position - $start_position));
            }
            $link = str_replace("\r", "", $link);
            $link = str_replace("\n", "", $link);
            $link = trim($link);
            $link = trim($link, '/');
            $urls[] = $link;
            $start = $end_position;
            $urls = array_unique($urls);
        } while (count($urls) !== $limit);
        return $urls;
    }

    /**
     * search next position and return as a array
     *
     * @param $html
     * @param $start
     * @return array
     */
    private function serachNext($html, $start): array
    {
        $position_collection['https://']  = strpos($html, 'https://', $start);
        $position_collection['http://']  = strpos($html, 'http://', $start);
        $position_collection['href=']  = strpos($html, 'href=', $start);
        $position_collection['src=']  = strpos($html, 'src=', $start);

        return $position_collection;
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

    public function fileOnly(array $extensions = ['*'])
    {
        $links = [];

        foreach ($this->urls as $url) {

            if (is_array($url)) {
                foreach ($url as $value) {
                    if ($extensions === ['*']) {
                        if (false != pathinfo(urldecode($value), PATHINFO_EXTENSION)) {
                            $links[] = urldecode($value);
                        }
                    } else {
                        foreach ($extensions as $extension) {
                            if (strtolower(trim($extension, '.')) === strtolower(pathinfo(urldecode($value), PATHINFO_EXTENSION))) {
                                $links[] = urldecode($value);
                            }
                        }
                    }
                }
            } else {
                if ($extensions === ['*']) {
                    if (false != pathinfo(urldecode($url), PATHINFO_EXTENSION)) {
                        $links[] = urldecode($url);
                    }
                } else {
                    foreach ($extensions as $extension) {
                        if (strtolower(trim($extension, '.')) === strtolower(pathinfo(urldecode($url), PATHINFO_EXTENSION))) {
                            $links[] = urldecode($url);
                        }
                    }
                }
            }
        }

        $this->urls = $links;
        return $this;
    }

    /**
     * sort urls
     *
     * @return object
     */
    public function sortURL(bool $desc = false): object
    {

        $urls = $this->urls;
        if ($desc) {
            foreach ($urls as $url) {
                if (is_array($url)) {
                    rsort($url);
                }
            }
            rsort($url);
        } else {
            foreach ($urls as $url) {
                if (is_array($url)) {
                    sort($url);
                }
            }
            sort($url);
        }

        $this->urls = $url;
        return $this;
    }
}

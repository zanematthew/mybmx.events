<?php

namespace App;

use Storage;

trait ShovelParserTrait
{
    /**
     * Remove leading, trailing white space and new new lines.
     *
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
    public function cleanText($text)
    {
        return trim(preg_replace('/\s\s+/', ' ', $text));
    }

    public function pageRange($pageRange)
    {
        $pageRange = [1,1];

        // Since there isn't an ask/choice, we need to parse the page range.
        if ($pageRange != "1") {
            $pageRange = explode('-', $pageRange);
        }

        return $pageRange;
    }

    /**
     * Given a URI derive the event ID
     *
     * @param  string $uri
     * @return int          The event ID
     */
    public function eventIdFromUri($uri)
    {
        return explode('?', explode('/', $uri)[3])[0];
    }

    public function latLongFromLinkHref($href)
    {
        preg_match("~\?q=(.*?)\&~", $href, $matches);
        list($lat, $long) = explode(',', $matches[0]);
        return [
            'lat' => floatval(str_replace('?q=', '', $lat)),
            'long' => floatval($long),
        ];
    }

    // An address may look like the following, note the second has
    // no street, and two br tags.
    // <p>26600 Budds Creek Rd<br>Mechanicsville,  Md 20659<br>Usa</p>
    // <p>Egg Harbor Township,  Nj 08234<br>Usa</p>
    public function locationFromP($location)
    {
        $street = null;
        $city = null;
        $state = null;
        $zip = null;
        $country = null;

        if (substr_count($location, '<br>') == 2) {
            list($street, $cityStateZip, $country) = explode('<br>', $location);
            list($city, $state, $zip) = explode(' ', str_replace(', ', '', $cityStateZip));
        }

        if (substr_count($location, '<br>') == 1) {
            list($city, $stateZipCountry) = explode(',  ', $location);
            list($state, $zipCountry) = explode(' ', $stateZipCountry);
            list($zip, $country) = explode('<br>', $zipCountry);
        }

        return array_map('strip_tags', [
            'street'  => $street,
            'city'    => $city,
            'state'   => strtoupper($state),
            'zip'     => $zip,
            'country' => strtoupper($country),
        ]);
    }

    public function districtFromString($string)
    {
        return trim(explode(":", $string)[1]);
    }

    public function venueId($uri = null)
    {
        return last(explode('/', $uri));
    }

    /**
     * Parse the date based on the following format:
     *     [some date] - [some other date]
     *
     * @param  string $date The date to parse
     * @return mixed        If two dates returns an array, as start/end
     */
    public function date($date)
    {
        if (strpos($date, '-') !== false) {
            $date = array_map('trim', explode('-', $date));
        } else {
            $date = trim($date);
        }

        return $date;
    }

    public function eventIdFromUrl($url = null)
    {
        $parsed = parse_url($url);
        return last(explode('bmx_races/', $parsed['path']));
    }
}

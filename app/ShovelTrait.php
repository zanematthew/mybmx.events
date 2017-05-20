<?php

namespace App;

use Storage;

trait ShovelTrait
{

    public $url = 'https://www.usabmx.com/site';

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

    /**
     * @SuppressWarnings(PHPMD) Needed so PHPMD allows for default Laravel usage of classes
     */
    public function saveToJson($name, $data, $type)
    {
        return Storage::disk('local')->put("public/{$type}/{$name}.json", json_encode($data));
    }

    public function parsedPageRange($pageRange)
    {
        $parsedPageRange = [1,1];

        // Since there isn't an ask/choice, we need to parse the page range.
        if ($pageRange != "1") {
            $parsedPageRange = explode('-', $pageRange);
        }

        return $parsedPageRange;
    }

    public function validYears()
    {
        $years     = [];
        $yearStart = 2004;
        $yearEnd   = 2016;

        while ($yearStart <= $yearEnd) {
            $years[] = $yearStart++;
        }
        $years[] = 'Upcoming';

        return array_reverse($years);
    }

    /**
     * Given a URI derive the event ID
     *
     * @param  string $uri
     * @return int          The event ID
     */
    public function parseIdFromUri($uri)
    {
        return explode('?', explode('/', $uri)[3])[0];
    }

    public function parseLatLongFromLinkHref($href)
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
    public function parseLocationFromP($location)
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


    public function parseDistrictFromString($string)
    {

        return trim(explode(":", $string)[1]);
    }

    public function states()
    {
        return [
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
        ];
    }
}

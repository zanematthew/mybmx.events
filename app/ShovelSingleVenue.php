<?php

namespace App;

use Goutte\Client as GoutteClient;

class ShovelSingleVenue extends Shovel
{
    use ShovelTrait;

    /**
     * Parse the Venue contact information.
     *
     * @return array An empty array on failure. On success;
     *                    'email'             => 'some value',
     *                    'track_phone'       => 'some value',
     *                    'primary_contact'   => 'some value',
     *                    'secondary_contact' => 'some value',
     */
    public function contact(): array
    {
        $contact = $this->filter('#track_contact ul li')->each(function ($node) {
            list($title, $content) = explode(':', $node->filter('li')->text());
            return [snake_case($title) => $this->aggressiveTrim($content)];
        });

        $tmp = [];

        foreach ($contact as $c) {
            foreach ($c as $k => $v) {
                $tmp[$k] = $v;
            }
        }

        return $tmp;
    }

    /**
     * Parse the venue Location.
     *
     * An address may look like the following, note the second has
     * no street, and two br tags.
     * <p>26600 Budds Creek Rd<br>Mechanicsville,  Md 20659<br>Usa</p>
     * <p>Egg Harbor Township,  Nj 08234<br>Usa</p>
     *
     * @return array An empty array on failure. On success;
     *                  'street'    => 'some value',
     *                  'city'      => 'some value',
     *                  'stateAbbr' => 'some value',
     *                  'zipCode'   => 'some value',
     *                  'country'   => 'some value',
     */
    public function parseLocationFromP(): array
    {
        $location = $this->filter('#track_location p')->eq(0)->html();

        $street  = null;
        $city    = null;
        $state   = null;
        $zip     = null;
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
            'street'    => $street,
            'city'      => $city,
            'stateAbbr' => strtoupper($state),
            'zipCode'   => $zip,
            'country'   => strtoupper($country),
        ]);
    }

    public function getStreet(): string
    {
        return $this->parseLocationFromP()['street'];
    }

    public function getCity(): string
    {
        return $this->parseLocationFromP()['city'];
    }

    public function getStateAbbr(): string
    {
        return $this->parseLocationFromP()['stateAbbr'];
    }

    public function getZipCode(): string
    {
        return $this->parseLocationFromP()['zipCode'];
    }

    public function getCountry(): string
    {
        return $this->parseLocationFromP()['country'];
    }

    public function parseLinksUri($linkText = null): string
    {
        return array_values(array_filter($this->filter('#track_location p')->each(function ($node) use ($linkText) {
            return str_contains($node->text(), $linkText) ? $node->filter('a')->attr('href') : '';
        })))[0];
    }

    public function parseScheduleUri(): string
    {
        return $this->parseLinksUri('Schedule');
    }

    public function parseMapUri(): string
    {
        return $this->parseLinksUri('Map');
    }

    public function parseWebsiteUri(): string
    {
        return $this->parseLinksUri('Website');
    }

    public function parseLogo(): string
    {
        return $this->filter('#track_location img')->attr('src');
    }

    public function parseDescription(): string
    {
        return implode(' ', $this->filter('#track_contact p')->each(function ($node) {
            return $node->text();
        }));
    }

    public function parseDistrict(): string
    {
        return trim(last(explode(':', $this->filter('#track_district')->text())));
    }

    public function parseLatLongMapsUri(): array
    {
        preg_match("~\?q=(.*?)\&~", $this->parseMapUri(), $matches);
        list($lat, $long) = explode(',', $matches[0]);
        return [
            'lat'  => floatval(str_replace('?q=', '', $lat)),
            'long' => floatval($long),
        ];
    }

    public function parseName(): string
    {
        return $this->aggressiveTrim(current(explode('<', $this->filter('#main_content h1')->html())));
    }
}

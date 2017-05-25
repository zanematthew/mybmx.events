<?php

namespace App;

use Storage;

trait ShovelTrait
{

    protected $sourceUrl = 'https://www.usabmx.com/site';

    protected $venueSlug = '/bmx_tracks';

    protected $eventSlug = '/bmx_races';

    protected $eventTypes = [
        'National' => [
            'section_id' => 228,
        ],
        'Earned Double' => [
            'section_id' => 95,
        ],
        'Gold Cup' => [
            'section_id' => 24,
        ],
        'Race for Life' => [
            'section_id' => 19,
        ],
        'State' => [
            'section_id' => 23,
        ],
    ];

    /**
     * @SuppressWarnings(PHPMD) Needed so PHPMD allows for default Laravel usage of classes
     */
    public function saveToJson($name, $data, $type)
    {
        return Storage::disk('local')->put("public/{$type}/{$name}.json", json_encode($data));
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

    public function isYearValid($year = null): bool
    {
        return (in_array($year, $this->validYears(), true));
    }

    public function isStateValid($state = null): bool
    {
        return (in_array(strtoupper($state), array_keys($this->states()), true));
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
            // 'DC' => 'District Of Columbia',
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

    public function aggressiveTrim($content = null): string
    {
        return trim(preg_replace('/\s\s+/', ' ', $content));
    }

    public function buildUrl($type, $year = null, $page = null): string
    {
        if ($this->isYearValid($year) === false) {
            return '';
        }

        $sectionId = $this->eventTypes[$type]['section_id'];

        switch ($sectionId) {
            case 228:
                $additionalParams = ['category' => strtoupper($type)];
                break;
            case 95:
                $additionalParams = ['series_race_type' => $type];
                break;
            case 24:
                $additionalParams = ['goldcup' => 1];
                break;
            case 19:
                $additionalParams = ['series_race_type' => $type];
                break;
            case 23:
                $additionalParams = ['filter_state' => 1];
                break;
            default:
                $additionalParams = null;
                break;
        }

        $pastOnly = 1;
        $yearFix  = $year;

        if ($year == 'Upcoming') {
            $pastOnly = 0;
            $yearFix  = 'UPCOMING';
        }

        return $this->sourceUrl . $this->eventSlug . '?' . http_build_query(array_merge([
            'section_id' => $sectionId,
            'year'       => $yearFix,
            'past_only'  => $pastOnly,
            'page'       => empty($page) ? 1 : $page,
        ], $additionalParams));
    }

    public function nationalUrl($year = 'Upcoming', $page = 1): string
    {
        return $this->buildUrl('National', $year, $page);
    }

    public function stateUrl($year = 'Upcoming', $page = 1): string
    {
        return $this->buildUrl('State', $year, $page);
    }

    public function earnedDoubleUrl($year = 'Upcoming', $page = 1): string
    {
        return $this->buildUrl('Earned Double', $year, $page);
    }

    public function raceForLifeUrl($year = 'Upcoming', $page = 1): string
    {
        return $this->buildUrl('Race for Life', $year, $page);
    }

    public function goldCupUrl($year = 'Upcoming', $page = 1): string
    {
        return $this->buildUrl('Gold Cup', $year, $page);
    }
}

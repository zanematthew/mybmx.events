<?php

namespace App;

use Storage;

trait ShovelTrait
{
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
        $yearEnd   = date('Y');

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
}

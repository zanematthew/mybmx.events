<?php

namespace App;

use Goutte;

class ShovelEvent
{

    use ShovelTrait;
    use ShovelParserTrait;

    public $eventTypes = [
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

    private $slug = '/bmx_races';

    /**
     * Given an event ID retrieve the Type
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getEventTypeFromId($eventId)
    {
        return array_keys($this->eventTypes)[array_search(
            $eventId,
            array_column(
                $this->eventTypes,
                'section_id'
            )
        )];
    }

    /**
     * Build the HTTP query params
     *
     * @param  string $type The event Type
     * @return string       URL encoded parameters
     */
    public function buildUrl($type, $page = null, $year = null)
    {
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

        return $this->eventUrl() . '?' . http_build_query(array_merge([
            'section_id' => $sectionId,
            'year'       => $yearFix,
            'past_only'  => $pastOnly,
            'page'       => empty($page) ? 1 : $page,
        ], $additionalParams));
    }

    /**
     * Gets start date
     *
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    public function getStartDate($date)
    {
        $date = $this->date($date);
        if (is_array($date)) {
            $date = current($date);
        }
        return $date;
    }

    /**
     * Gets the end date
     *
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    public function getEndDate($date)
    {
        $date = $this->date($date);
        if (is_array($date)) {
            $date = last($date);
        }

        return $date;
    }

    /**
     * Get events
     *
     * @param  string $url [description]
     * @return [type]       [description]
     */
    public function getEvents($url = null)
    {

        $crawler = Goutte::request('GET', $url);
        $events = $crawler->filter('.event_details')->each(
            function ($node) {

                $title     = null;
                $startDate = null;
                $endDate   = null;
                $trackId   = null;
                $eventId   = null;

                if ($node->filter('.event_title')->count()) {
                    $title = $this->cleanText($node->filter('.event_title')->text());
                    $eventId = $this->eventIdFromUri($node->filter('.event_title a')->attr('href'));
                }

                if ($node->filter('.event_date')->count()) {
                    $date = $this->cleanText($node->filter('.event_date')->text());
                    $startDate = $this->getStartDate($date);
                    $endDate = $this->getEndDate($date);
                }

                if ($node->filter('.event_location a')->count()) {
                    $trackId = $this->venueId($node->filter('.event_location a')->first()->attr('href'));
                }

                return [
                    'id'         => $eventId,
                    'title'      => $title,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                    'track' => [
                        'id'   => $trackId
                    ]
                ];
            }
        );

        return $events;
    }

    public function eventUrl()
    {
        return $this->url . $this->slug;
    }
}

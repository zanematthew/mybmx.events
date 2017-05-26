<?php

namespace App;

class ShovelBulkEventIdByType extends AbstractShovelClient
{
    use ShovelTrait;

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

    public function __construct($type = null, $year = null, $page = null)
    {
        parent::__construct($this->buildUrl($type, $year, $page));
    }

    public function buildUrl($type = null, $year = null, $page = null): string
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

        if ($year == 'Upcoming' || $year == date('Y')) {
            $pastOnly = 0;
            $yearFix  = 'UPCOMING';
        }

        return 'https://www.usabmx.com/site/bmx_races?' . http_build_query(array_merge([
            'section_id' => $sectionId,
            'year'       => $yearFix,
            'past_only'  => $pastOnly,
            'page'       => empty($page) ? 1 : $page,
        ], $additionalParams));
    }

    public function eventIds(): array
    {
        $links = $this->filter('.event_title')->each(function ($node) {
            return $node->filter('a')->eq(0)->attr('href');
        });

        return array_map(function ($link) {
            return (int) explode('/', explode('?', $link)[0])[3];
        }, $links);
    }

    public function maxPage(): int
    {
        return max($this->filter('.pagination li')->each(function ($node) {
            return (int) $node->filter('li')->text();
        }));
    }
}

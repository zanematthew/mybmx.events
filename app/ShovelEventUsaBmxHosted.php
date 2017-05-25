<?php

namespace App;

class ShovelEventUsaBmxHosted extends ShovelEvent
{

    // Only for Nationals/USABMX hosted events
    // These have a START DATE, and END DATE.
    public function date()
    {
        $date = $this->filter('#event_date')->eq(0)->text();
        if (strpos($date, '-') !== false) {
            $date = array_map('trim', explode('-', $date));
        } else {
            $date = trim($date);
        }
        return $date;
    }

    public function startDate()
    {
        return current($this->date());
    }

    public function endDate()
    {
        return last($this->date());
    }

    public function parseResource($resource = null)
    {
        $resources = $this->filter('.race_resources li')->each(function ($node) use ($resource) {
            // Note, can't use `snake_case()`, because that makes BMX into b_m_x
            return [str_replace(' ', '_', strtolower($node->text())) => $node->filter('a')->attr('href')];
        });

        $tmp = [];
        // TODO figure out why the array returned is nested(?)
        foreach ($resources as $r){
            foreach ($r as $k => $v) {
                $tmp[$k] = $v;
            }
        }
        return $tmp[$resource] ?? null;
    }

    public function flyerUri()
    {
        return $this->parseResource('event_flyer');
    }

    public function eventScheduleUri()
    {
        return $this->parseResource('event_schedule');
    }

    public function hotelUri()
    {
        return $this->parseResource('bmx_hotels');
    }

    // public function preSignUri(){}
}

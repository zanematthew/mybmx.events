<?php

namespace App;

class ShovelEventNonUsaBmxHosted extends ShovelEvent
{
    // Only for earned double, rfl, gcq, state race)(){}
    public function parseDescription($text = null): string
    {
        if (empty($text)) {
            return '';
        }

        $textArray = $this->filter('#event_description li')->each(function ($node) use ($text) {
            return $node->filter('li')->text();
        });

        $found = null;
        foreach ($textArray as $item) {
            if (str_contains($item, $text)) {
                $found = $item;
            }
        }
        return trim(last(explode(': ', $found)));
    }

    public function fee()
    {
        return $this->parseDescription('Entry Fee');
    }

    public function registrationStartTime()
    {
        return $this->parseDescription('Registration Begins');
    }

    public function registrationEndTime()
    {
        return $this->parseDescription('Registration Ends');
    }
}

<?php

namespace App\Views;

use DateTime;

class TimePeriodView extends View
{
    private static string $TIME = 'H:i';
    public function __construct(DateTime $start, ?DateTime $end)
    {
        if (!$end) {
            $this->set($start->format(TimePeriodView::$TIME));
            return;
        }
        $this->set("{$start->format(TimePeriodView::$TIME)}  \u{2013}  {$end->format(TimePeriodView::$TIME)}");
    }
}

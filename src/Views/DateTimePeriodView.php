<?php

namespace App\Views;

use DateTime;

class DateTimePeriodView extends View
{
    private static string $DATE_TIME = 'd.m.Y | H:i';
    private static string $TIME = 'H:i';
    public function __construct(DateTime $start, ?DateTime $end)
    {
        if (!$end) {
            $this->set($start->format(DateTimePeriodView::$DATE_TIME));
            return;
        }
        $startView = $start->format(DateTimePeriodView::$DATE_TIME);
        $endView = $start->format('dmY') == $end->format('dmY') ?
            $end->format(DateTimePeriodView::$TIME) :
            $end->format(DateTimePeriodView::$DATE_TIME);
        $this->set("$startView  \u{2013}  $endView");
    }
}

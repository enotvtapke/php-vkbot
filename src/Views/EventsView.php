<?php

namespace App\Views;

use App\Domain\Entities\Event;

class EventsView extends View
{
    /**
     * @var string[]
     */
    private static array $DAYS_OF_THE_WEEK = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    /**
     * @param array<Event> $events
     */
    public function __construct(array $events)
    {
        if (count($events) == 0) {
            $this->set("No events");
            return;
        }

        $groups = [];
        $group = [];
        $date = $events[0]->getStart()->format('dmY');
        foreach ($events as $event) {
            $curDate = $event->getStart()->format('dmY');
            if ($curDate != $date) {
                $groups[] = $group;
                $group = [];
                $date = $curDate;
            } else {
                $group[] = $event;
            }
        }
        $groups[] = $group;

        $res = "";
        foreach ($groups as $group) {
            $dayOfTheWeek = EventsView::$DAYS_OF_THE_WEEK[(int)$group[0]->getStart()->format('w')];
            $res = $res . $dayOfTheWeek . ' ' . $group[0]->getStart()->format('d.m.Y') . "\n";
            foreach ($group as $event) {
                $res = $res . (new EventView($event))->render() . "\n";
            }
            $res = $res . "\n";
        }
        $res = trim($res);
        $this->set($res);
    }
}

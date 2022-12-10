<?php

namespace App\Views;

use App\Domain\Entities\Event;

class EventView extends View
{
    public function __construct(Event $event)
    {
        $period = new TimePeriodView($event->getStart(), $event->getEnd());
        $tags = new TagsView($event->getTags());
        $this->set(
            <<<A
            {$period->render()} | {$event->getName()} {$tags->render()}
            A
        );
    }
}

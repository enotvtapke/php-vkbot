<?php

namespace App\Views;

use App\Domain\Entities\Tag;

class TagView extends View
{
    public function __construct(Tag $tag)
    {
        $this->set("#{$tag->getName()}");
    }
}

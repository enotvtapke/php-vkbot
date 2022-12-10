<?php

namespace App\Views;

use App\Domain\Entities\Tag;

class TagsView extends View
{
    /**
     * @param array<Tag> $tags
     */
    public function __construct(array $tags)
    {
        $this->set(implode(' ', array_map(fn($tag) => (new TagView($tag))->render(), $tags)));
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use DateTime;
use DateTimeInterface;

class Event
{
    private ?int $id = null;

    private string $name;

    private DateTime $start;

    private ?DateTime $end = null;

    /**
     * @var array<Tag>
     */
    private array $tags = [];

    public function __construct(?int $id, string $name, DateTime $start, ?DateTime $end, array $tags = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->start = $start;
        $this->end = $end;
        $this->tags = $tags;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    /**
     * @return array<Tag>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function __toString()
    {
        $id = $this->id ?? 'null';
        $start = $this->start->format(DateTimeInterface::ATOM);
        $end = $this->end ? null : $this->start->format(DateTimeInterface::ATOM);
        $tags = implode(', ', $this->tags);
        return "Event { id: $id, name: $this->name, start: $start, end: $end, tags: [$tags] }";
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }
}

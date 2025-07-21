<?php

namespace ArmCm\TaskMemory;

class Task
{
    private int $id;
    public string $title;
    public string $description;
    private string $status;

    const string DONE = 'done';
    const string IN_PROGRESS = 'in progress';
    const string PENDING = 'pending';

    public function __construct(string $title, string $description)
    {
        $this->title = $title;
        $this->description = $description;
        $this->status = self::PENDING;
    }

    public function assignId(int $id): void
    {
        $this->id = $id;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function done(): void
    {
        $this->status = self::DONE;
    }

    public function starting(): void
    {
        $this->status = self::IN_PROGRESS;
    }

    public function update(?string $title = null, ?string $description = null): void
    {
        if (!empty(trim((string) $title))) {
            $this->title = $title;
        }

        if (!empty(trim((string) $description))) {
            $this->description = $description;
        }
    }

    public function currentStatus(): string
    {
        return $this->status;
    }

    public function matches(string $attribute, string|int $value): bool
    {
        return $this->toArray()[$attribute] === $value;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}

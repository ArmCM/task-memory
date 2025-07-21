<?php

namespace ArmCm\TaskMemory;

use InvalidArgumentException;

class TaskCollection
{
    private int $counter = 0;
    private array $tasks = [];

    public function add(Task|array $tasks): void
    {
        $tasks = is_array($tasks) ? $tasks : [$tasks];

        foreach ($tasks as $task) {
            if (! $task instanceof Task) {
                throw new InvalidArgumentException('Only Task instances are allowed.');
            }

            $task->assignId(++$this->counter);
            $this->tasks[] = $task;
        }
    }

    public function all(): array
    {
        return $this->tasks;
    }

    public function findBy(string $attribute, string|int $value): array
    {
        $this->validate($attribute, $value);

        $matches = $this->filter(function (Task $task) use ($attribute, $value) {
            return $task->matches($attribute, $value);
        });

        if (empty($matches)) {
            throw new \RuntimeException('No records found matching.');
        }

        return $matches;
    }

    public function count(): int
    {
        return count($this->tasks);
    }

    public function toArray(): array
    {
        return array_map(function (Task $task) {
            return [
                'id' => $task->id(),
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->currentStatus(),
            ];
        }, $this->all());
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    private function validate(string $attribute, string|int $value): void
    {
        match($attribute) {
            'status' => $this->validateStatus($value),
            'id' => $this->validateId($value),
            'title' => $this->validateTitle($value),
            'description' => $this->validateDescription($value),
            default => null,
        };
    }

    private function validateStatus(string $status): void
    {
        $this->validateNotEmpty($status);

        if (!in_array($status, ['pending', 'done', 'in progress'], true)) {
            throw new InvalidArgumentException('Invalid status.');
        }
    }

    private function validateTitle(string $title): void
    {
        $this->validateNotEmpty($title);
    }

    private function validateDescription(string $description): void
    {
        $this->validateNotEmpty($description);
    }

    private function validateId(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Invalid id.');
        }
    }

    private function validateNotEmpty($value): void
    {
        if (empty(trim($value))) {
            throw new InvalidArgumentException("Not allowed empty value.");
        }
    }

    private function filter(callable $callback): array
    {
        return array_values(
            array_filter($this->all(), $callback)
        );
    }
}

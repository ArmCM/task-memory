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

    public function filterByStatus(string $status): array
    {
        $this->validateStatus($status);

        return $this->filter( function (Task $task) use ($status) {
            return $task->currentStatus() === $status;
        });
    }

    public function filterById(int $id): array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Invalid id.');
        }

        return $this->filter( function (Task $task) use ($id) {
            return $task->id() === $id;
        });
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

    private function validateStatus(string $status): void
    {
        if (empty(trim($status))) {
            throw new InvalidArgumentException('Not allowed empty status.');
        }

        if (!in_array($status, ['pending', 'done', 'in progress'], true)) {
            throw new InvalidArgumentException('Invalid status.');
        }
    }

    private function filter(callable $callback): array
    {
        return array_values(
            array_filter($this->all(), $callback)
        );
    }
}

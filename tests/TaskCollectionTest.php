<?php

namespace ArmCm\TaskMemory\Tests;

use ArmCm\TaskMemory\Task;
use ArmCm\TaskMemory\TaskCollection;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TaskCollectionTest extends TestCase
{
    protected function setUp(): void
    {
        $taskCollection = new TaskCollection();
        $reflection = new \ReflectionClass(TaskCollection::class);
        $property = $reflection->getProperty('counter');
        $property->setAccessible(true);
        $property->setValue($taskCollection, 0);
    }

    #[Test]
    public function can_add_multiple_tasks_to_collection()
    {
        $taskA = new Task('example title A', 'example description A');
        $taskB = new Task('example title B', 'example description B');
        $taskC = new Task('example title C', 'example description C');

        $taskCollection = new TaskCollection();

        $taskCollection->add([$taskA, $taskB, $taskC]);

        $this->assertEquals(3, $taskCollection->count());
    }

    #[Test]
    public function throw_exception_when_adding_invalid_task()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only Task instances are allowed.');

        $taskCollection = new TaskCollection();
        $invalidTask = new \stdClass();

        $taskCollection->add([$invalidTask]);
    }

    #[Test]
    public function can_retrieve_all_tasks_from_collection()
    {
        $taskA = new Task('example title A', 'example description A');
        $taskB = new Task('example title B', 'example description B');
        $taskC = new Task('example title C', 'example description C');

        $taskCollection = new TaskCollection();
        $taskCollection->add([$taskA, $taskB, $taskC]);

        $tasks = $taskCollection->all();

        $this->assertEquals([
            'id' => 1,
            'title' => 'example title A',
            'description' => 'example description A',
            'status' => 'pending',
        ], $tasks[0]->toArray());

        $this->assertEquals([
            'id' => 3,
            'title' => 'example title C',
            'description' => 'example description C',
            'status' => 'pending',
        ], $tasks[2]->toArray());
    }

    #[Test]
    public function can_filter_task_by_status()
    {
        $pendingTask = new Task('example title A', 'example description A');
        $startedTask = new Task('example title B', 'example description B');
        $doneTask = new Task('example title C', 'example description C');

        $doneTask->done();
        $startedTask->starting();

        $taskCollection = new TaskCollection();

        $taskCollection->add([$pendingTask, $startedTask, $doneTask]);

        $result = $taskCollection->filterByStatus('pending');

        $this->assertEquals([
            'id' => 1,
            'title' => 'example title A',
            'description' => 'example description A',
            'status' => 'pending',
        ], $result[0]->toArray());
    }

    #[Test]
    public function throws_exception_when_status_is_empty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not allowed empty status.');

        $pendingTask = new Task('example title A', 'example description A');

        $taskCollection = new TaskCollection();

        $taskCollection->add($pendingTask);

        $taskCollection->filterByStatus(' ');
    }

    #[Test]
    public function can_filter_task_by_id()
    {
        $pendingTask = new Task('example title A', 'example description A');
        $startedTask = new Task('example title B', 'example description B');
        $doneTask = new Task('example title C', 'example description C');

        $doneTask->done();
        $startedTask->starting();

        $taskCollection = new TaskCollection();

        $taskCollection->add([$pendingTask, $startedTask, $doneTask]);

        $result = $taskCollection->filterById(1);

        $this->assertEquals([
            'id' => 1,
            'title' => 'example title A',
            'description' => 'example description A',
            'status' => 'pending',
        ], $result[0]->toArray());
    }

    #[Test]
    public function throws_exception_when_id_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid id.');

        $pendingTask = new Task('example title A', 'example description A');

        $taskCollection = new TaskCollection();

        $taskCollection->add($pendingTask);

        $taskCollection->filterById(-1);
    }

    #[Test]
    public function task_collection_can_be_serialized_to_array()
    {
        $task = new Task('valid title', 'valid description');
        $taskCollection = new TaskCollection();

        $taskCollection->add($task);

        $this->assertSame([
            [
                "id" => 1,
                "title" => "valid title",
                "description" => "valid description",
                "status" => "pending",
            ],
        ], $taskCollection->toArray());
    }

    #[Test]
    public function task_collection_can_be_serialized_to_json()
    {
        $task = new Task('valid title', 'valid description');
        $taskCollection = new TaskCollection();

        $taskCollection->add($task);

        $expectedJson = json_encode([
            [
                "id" => 1,
                "title" => "valid title",
                "description" => "valid description",
                "status" => "pending",
            ],
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $taskCollection->toJson());
    }
}

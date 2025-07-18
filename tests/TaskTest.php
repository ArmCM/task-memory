<?php

namespace ArmCm\TaskMemory\Tests;

use ArmCm\TaskMemory\Task;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    #[Test]
    public function allowed_mark_task_as_done()
    {
        $task = new Task('valid title', 'valid description');

        $task->done();

        $this->assertEquals('done', $task->currentStatus());
    }

    #[Test]
    public function allowed_mark_task_as_in_progress()
    {
        $task = new Task('valid title', 'valid description');

        $task->starting();

        $this->assertEquals('in progress', $task->currentStatus());
    }

    #[Test]
    public function can_update_title_and_description()
    {
        $task = new Task('initial title', 'initial description');

        $task->update('updated title', 'updated description');

        $this->assertEquals('updated title', $task->title);
        $this->assertEquals('updated description', $task->description);
        $this->assertEquals('pending', $task->currentStatus());
    }

    #[Test]
    public function allows_optional_arguments_when_updating()
    {
        $task = new Task('initial title', 'initial description');

        $task->update('updated title');

        $this->assertEquals('updated title', $task->title);
        $this->assertEquals('initial description', $task->description);
        $this->assertEquals('pending', $task->currentStatus());
    }

    #[Test]
    public function ignores_blank_description_on_update()
    {
        $task = new Task('initial title', 'initial description');

        $task->update('updated title', ' ');

        $this->assertEquals('updated title', $task->title);
        $this->assertEquals('initial description', $task->description);
        $this->assertEquals('pending', $task->currentStatus());
    }
}

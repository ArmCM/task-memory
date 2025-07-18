# Laravel Task memory

![Banner](https://banners.beyondco.de/Task%20Memory.png?theme=light&packageManager=composer+require&packageName=armcm%2Ftask-memory&pattern=plus&style=style_1&description=Manage+a+task+list+in+memory&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Fwww.php.net%2Fimages%2Flogos%2Fphp-logo.svg)

This package allows manage task in memory.

## Installation

You can install the package via composer:

```bash
composer require armcm/task-memory
```

## Usage
**Install in a project.**

```shell
mkdir demo-task-app

cd demo-task-app

composer init # Follow prompts

composer require arm-cm/task-memory
```

Create file **index php**

```
demo-task-app/
├── vendor/
├── composer.json
└── index.php
```

### Index.php
```php
<?php

require 'vendor/autoload.php';

use ArmCm\TaskMemory\Task;
use ArmCm\TaskMemory\TaskCollection;

$task = new Task('Write demo', 'Create a usage example');

$collection = new TaskCollection();
$collection->add($task);

print_r($collection->toArray());

echo $collection->toJson();
```

Run script

```
php index.php
```

## API Reference

### Methods that can be used in Task class

```php
use ArmCm\TaskMemory\Task;

$task = new Task('Title', 'Description');

$task->done();
$task->starting();
$task->currentStatus()

$task->update('New title', 'New description');
$task->update('New title');
```

### Methods that can be used in TaskCollection class

```php
use ArmCm\TaskMemory\TaskCollection;

$collection = new TaskCollection();

$collection->add(Task|array $tasks) — Adds one or more Task objects to the collection

$collection->all(): array — Returns all tasks as an array of Task objects

$collection->count(): int — Returns the total number of tasks in the collection

$collection->filterByStatus(string $status): array — Returns all tasks that match the given status

$collection->filterById(int $id): array — Returns all tasks that match the given ID

$collection->toArray(): array — Converts the task collection to an associative array

$collection->toJson(): string — Converts the task collection to a JSON string
```

### Testing

```bash
composer test
```

## Credits

-   [Armando Calderón](https://github.com/ArmCM)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

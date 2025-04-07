<?php

namespace App\Services;

use App\Enums\Param;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService
{
    /**
     * Get the latest incomplete tasks.
     *
     * @return Collection<int, Task>
     */
    public function getIncompleteTasks(): Collection
    {
        return Task::incomplete()->latest()->take(Param::PER_PAGE->value)->get();
    }

    /**
     * Create a new task.
     *
     * @return Task
     */
    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Mark a task as complete.
     *
     */
    public function markTaskAsComplete(Task $task): bool
    {
        if (!$task->completed) {
            $task->completed = true;
            return $task->save();
        }

        return false;
    }
}

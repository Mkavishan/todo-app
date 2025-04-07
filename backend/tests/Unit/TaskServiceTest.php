<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = new TaskService();
    }

    /**
     * Test for getIncompleteTasks() method.
     */
    public function test_get_incomplete_tasks(): void
    {
        // Create some tasks
        $task1 = Task::factory()->create(['completed' => false]);
        $task2 = Task::factory()->create(['completed' => false]);

        // Call the method
        $tasks = $this->taskService->getIncompleteTasks();

        // Assert that the returned collection contains the tasks
        $this->assertCount(2, $tasks);
        $this->assertTrue($tasks->contains($task1));
        $this->assertTrue($tasks->contains($task2));
    }

    /**
     * Test for createTask() method.
     */
    public function test_create_task(): void
    {
        // Create task data
        $data = [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
            'completed' => false,
        ];

        // Call createTask method
        $task = $this->taskService->createTask($data);

        // Assert that the task was created successfully
        $this->assertDatabaseHas((new Task())->getTable(), [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
            'completed' => false,
        ]);

        $this->assertInstanceOf(Task::class, $task);
    }

    /**
     * Test for markTaskAsComplete() method.
     */
    public function test_mark_task_as_complete(): void
    {
        // Create a task
        $task = Task::factory()->create(['completed' => false]);

        // Call markTaskAsComplete
        $this->taskService->markTaskAsComplete($task);

        // Refresh task and assert that it is marked as completed
        $task->refresh();
        $this->assertTrue($task->completed);
    }
}

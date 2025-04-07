<?php

namespace Tests\Feature;

use App\Enums\Messages;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test for fetching incomplete tasks (index method)
     */
    public function test_index(): void
    {
        $task1 = Task::factory()->create(['completed' => false]);
        $task2 = Task::factory()->create(['completed' => false]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment([
            'title' => $task1->title,
            'description' => $task1->description,
        ]);
        $response->assertJsonFragment([
            'title' => $task2->title,
            'description' => $task2->description,
        ]);
    }

    /**
     * Test for no incomplete tasks
     */
    public function test_index_no_tasks(): void
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    /**
     * Test for create new Task
     */
    public function test_store_new_task(): void
    {
        $data = [
            'title' => 'New Task',
            'description' => 'Test Description',
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'New Task');

        $this->assertDatabaseHas((new Task())->getTable(), ['title' => 'New Task']);
    }

    /**
     * Test for completing a task (complete method)
     */
    public function test_complete_task(): void
    {
        $task = Task::factory()->create(['completed' => false]);

        $response = $this->patchJson("/api/tasks/{$task->id}/complete");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Task marked as completed'
        ]);

        $task->refresh();
        $this->assertTrue($task->completed);
    }

    /**
     * Test for completing an already completed task
     */
    public function test_complete_already_completed_task(): void
    {
        $task = Task::factory()->create(['completed' => true]);

        $response = $this->patchJson("/api/tasks/{$task->id}/complete");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Task marked as completed'
        ]);

        $task->refresh();
        $this->assertTrue($task->completed);
    }

    /**
     * Test for completing a non-existent task
     */
    public function test_complete_task_not_found(): void
    {
        $response = $this->patchJson("/api/tasks/999999/complete");

        $response->assertStatus(404);
    }

    /**
     * Test for validation errors (422)
     */
    public function test_validation_error_handling(): void
    {
        $data = [
            'title' => '',
            'description' => 'Test description',
            'completed' => false,
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Test for handling server errors (500) when creating a task.
     */
    public function test_exception_handling_when_storing_task(): void
    {
        // Mock the TaskService to throw an exception during task creation
        $taskServiceMock = \Mockery::mock(TaskService::class);
        $taskServiceMock->shouldReceive('createTask')
            ->once()
            ->andThrow(new \Exception('Task creation failed'));

        // Bind the mock service to the application container
        $this->app->instance(TaskService::class, $taskServiceMock);

        // Prepare valid data for a new task
        $data = [
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'completed' => false,
        ];

        // Make a POST request to create a new task
        $response = $this->postJson('/api/tasks', $data);

        // Assert that the response status is 500 (Internal Server Error)
        $response->assertStatus(500);

        // Assert that the response contains the correct error message
        $response->assertJson([
            'error' => Messages::SERVER_ERROR->value,  // The message returned from the catch block
        ]);
    }

    /**
     * Test for handling server errors (500) when completing a task.
     */
    public function test_exception_handling_when_completing_task(): void
    {
        // Mock the TaskService to throw an exception during task creation
        $taskServiceMock = \Mockery::mock(TaskService::class);
        $taskServiceMock->shouldReceive('markTaskAsComplete')
            ->once()
            ->andThrow(new \Exception('Task completion failed'));

        // Bind the mock service to the application container
        $this->app->instance(TaskService::class, $taskServiceMock);

        $task = Task::factory()->create(['completed' => false]);

        // Make a PATCH request to complete the Task
        $response = $this->patchJson("/api/tasks/$task->id/complete");

        // Assert that the response status is 500 (Internal Server Error)
        $response->assertStatus(500);

        // Assert that the response contains the correct error message
        $response->assertJson([
            'error' => Messages::SERVER_ERROR->value,  // The message returned from the catch block
        ]);
    }
}

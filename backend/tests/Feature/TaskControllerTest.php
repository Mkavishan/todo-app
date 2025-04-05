<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task(): void
    {
        $data = [
            'title' => 'New Task',
            'description' => 'Test Description',
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'New Task');

        $this->assertDatabaseHas('task', ['title' => 'New Task']);
    }

    public function test_can_get_latest_five_tasks(): void
    {
        Task::factory()->count(10)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_complete_task(): void
    {
        $task = Task::factory()->create(['completed' => false]);

        $response = $this->patchJson("/api/tasks/{$task->id}/complete");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task marked as completed']);

        $this->assertDatabaseHas('task', [
            'id' => $task->id,
            'completed' => true,
        ]);
    }
}

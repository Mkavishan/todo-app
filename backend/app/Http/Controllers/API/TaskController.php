<?php

namespace App\Http\Controllers\API;

use App\Enums\Messages;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    /**
     * Display a listing of incomplete tasks.
     *
     * @return TaskCollection
     */
    public function index(): TaskCollection
    {
        $tasks = $this->taskService->getIncompleteTasks();

        return new TaskCollection($tasks);
    }

    /**
     * New task store method.
     *
     * @return JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated());

            return response()->json(['data' => new TaskResource($task)], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json(['error' => Messages::SERVER_ERROR->value], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the given Task as completed method.
     *
     * @return JsonResponse
     */
    public function complete(Task $task): JsonResponse
    {
        try {
            $this->taskService->markTaskAsComplete($task);

            return response()->json(['message' => Messages::COMPLETED->value], Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json(['error' => Messages::SERVER_ERROR->value], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

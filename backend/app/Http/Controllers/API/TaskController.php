<?php

namespace App\Http\Controllers\API;

use App\Enum\Messages;
use App\Enum\Param;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Tasks list API method.
     */
    public function index(): TaskCollection
    {
        return new TaskCollection(Task::where('completed', false)->latest()->take(Param::PER_PAGE->value)->get());
    }

    /**
     * New task store method.
     */
    public function store(TaskRequest $request): TaskResource | JsonResponse
    {
        try {
            $task = Task::create($request->validated());

            return new TaskResource($task);
        } catch (\Throwable $e) {
            return response()->json(['error' => Messages::SERVER_ERROR->value], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified Task as completed method.
     */
    public function complete(Request $request, Task $task): JsonResponse
    {
        try {
            $task->completed = true;
            $task->save();

            return response()->json(['message' => 'Task marked as completed']);
        } catch (\Throwable $e) {
            return response()->json(['error' => Messages::SERVER_ERROR->value], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

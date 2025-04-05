<?php

namespace Tests\Unit;

use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;


class TaskRequestTest extends TestCase
{
    public function test_valid_data_passes_validation(): void
    {
        $request = new TaskRequest();

        $data = [
            'title' => 'Test Task',
            'description' => 'Test description',
        ];

        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_data_fails_validation(): void
    {
        $request = new TaskRequest();

        $data = [
            'title' => '', // required
        ];

        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
    }
}

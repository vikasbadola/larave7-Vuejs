<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TaskTest extends TestCase
{
    /**
     * Test creating a new task
     */
    public function testCreateTask()
    {
        $user = factory('App\User')->create();
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token",
            'Accept' => 'application/json'];
        $payload = [
            'name' => 'complete assignment',
            'desc' => 'laravel-vue assignment',
            'status' => 'P',
        ];
        $this->json('post', '/api/task', $payload, $headers)
            ->assertStatus(201)
            ->assertJsonStructure([]);
    }
    
    /**
     * Test edit a task
     */
    public function testEditTask()
    {
        $user = factory('App\User')->create();
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token",
            'Accept' => 'application/json'];
        $task = factory('App\Task')->create([
            'userID' => $user->id,
            'name' => 'complete assignment',
            'desc' => 'laravel-vue assignment',
            'status' => 'P',
        ]);
        $payload = [
            'userID' => $user->id,
            'name' => 'complete assignment - Edited',
            'desc' => 'laravel-vue assignment - Edited',
            'status' => 'C',
        ];
        $this->json('post', '/api/task?id='. $task->id, $payload, $headers)
            ->assertStatus(201)
            ->assertJsonStructure([]);
    }
}

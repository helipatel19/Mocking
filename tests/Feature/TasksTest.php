<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\TaskController;
use App\Task;
Use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;

class TasksTest extends TestCase
{
    use WithFaker,DatabaseTransactions;

    public $mockTask;
    public $mockValidator;
    public $mockTaskController;

    /**
     * Setup test environment
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->app['validator'];
        $this->mockTaskController = \Mockery::mock(TaskController::class)->makePartial();
    }

    /**
     * Clear test environment before starting test
     */

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * check if the task details are valid.
     * @test
     */

    public function validate_task_details(){

        $title = $this->faker->name;
        $description = $this->faker->paragraph;

        $data = [
            'title' => $title,
            'description' => $description,
        ];
        //validation rules
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
        //validate request data
        $validate = $this->mockValidator->make($data, $rules);
        $this->assertTrue($validate->passes());
    }

    /** @test */

    public function authenticated_user_can_view_all_tasks()
    {
        //Given we have a signed in user
        $this->actingAs(factory('App\User')->create());

        //And a task which is created by the user
        $task = factory('App\Task')->make();

        $tasks = $this->mockTaskController->index();

        $this->assertNotEmpty($tasks);
    }

    /** @test */

    public function authenticated_user_can_add_tasks(){

        //Given we have a signed in user
        $this->actingAs(factory('App\User')->make());

        //And a task which is created by the user
        $task = factory('App\Task')->create();

        $request = new Request($task->toArray());

        $mockResult = $this->mockTaskController->store($request);

        $this->assertDatabaseHas('tasks',$task->toArray());
    }

    /** @test */

    public function authenticated_user_can_update_task()
    {
        //Given we have a signed in user
        $this->actingAs(factory('App\User')->make());

        //And a task which is created by the user
        $task = factory('App\Task')->create();

        $request = new Request($task->toArray());

        $mockResult = $this->mockTaskController->update($request);

        $this->assertTrue($mockResult,true);

    }

    /** @test */

    public function authenticated_user_can_delete_task()
    {
        //Given we have a signed in user
        $this->actingAs(factory('App\User')->create());

        //And a task which is created by the user
        $task = factory('App\Task')->create();

        $request = new Request($task->toArray());

        $id = $request->id;

        $mockResult = $this->mockTaskController->destroy($id);

        $this->assertTrue($mockResult,true);

    }
}

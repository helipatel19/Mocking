# Mocking

### What is Mocking

when testing of laravel application, we wish to create replica of our application so that they are not actually executed during a given test. For instance, when testing a controller that dispatches an event, you may wish to mock the event listeners so they are not actually executed during the test. This allows you to only test the controller's HTTP response without worrying about the execution of the event listeners, since the event listeners can be tested in their own test case.

Laravel provides you with helpers for mocking events, jobs, and facades out of the box. These helpers primarily provide a convenience layer over Mockery so that you do not have to manually make complicated Mockery method calls. You can also use PHPUnit or Mockery to create your own mocks or spies.

### Install Mockery

Mockery can be installed using Composer or by cloning it from its GitHub repository. 

to install Mockery using composer ,first add Mockery to your composer.json file as below:

    {
        "require-dev": {
            "mockery/mockery": "dev-master"
        }
    }
    
Then, run ``composer install`` command from terminal.

### Mock Testing in Laravel

**Step 1 : Install Fresh Laravel Project**

    composer create-project –prefer-dist  laravel/laravel MockingExample

Now, set up the database in the ``.env`` file.

We will understand mocking through the example of TasksTest. In order to understand that we need to make following files.

1. Model File

2. Controller File

3. Migration File.

      
    php artisan make:model Task -mc

Above command will create Model , Controller as well as Migration file.

Modify the up() method of the migration file.

Now, go to the terminal and hit the following command.

    php artisan migrate
  
**Step 2 : Create TasksTest test case**

Initially, we need to create setup() method to set up test environment.
Here, we have mocked TaskController class to use it's methods for testing along with that we have also mocked Validator to check for validations.

    public function setUp(): void
    {
        parent::setUp();
    
        $this->mockValidator = $this->app['validator'];
        $this->mockTaskController = \Mockery::mock(TaskController::class)->makePartial();
    }

This static call cleans up the Mockery container used by the current test, and run any verification tasks needed for our expectations.

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

**Step 3 : create a test case method to validate all the tasks**

In this test case method we are checking if the task details are valid. 

     public function validate_task_details(){
           
          // Given we have a title and a description.        
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
    
**Step 4 : create a test case method to add tasks**

Here, we are using store() method of TaskController through mocking and asserting that task data exists in the database.

    public function authenticated_user_can_add_tasks(){
       
         //Given we have a signed in user
         $this->actingAs(factory('App\User')->make());
        
         //And a task which is created by the user
         $task = factory('App\Task')->create();
                
        $request = new Request($task);

        $mockResult = $this->mockTaskController->store($request);
        
        $this->assertDatabaseHas('tasks',$task);

    }
    
**Step 5 : create a test case method to update the task**

This test case uses update method of TaskController through mocking. we are passing $request into update method and we will assert that task is updated into database.

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

**Step 6 : create a test case method to delete the task**

Authorized user should be able to delete the tasks using destroy method of TaskController.

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
    
Run the tests, and you should get green !



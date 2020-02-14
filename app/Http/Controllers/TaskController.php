<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     *  get all task data and display from the database.
     */

    public function index()
    {
        $task = Task::all();
        return $task;
    }

    /**
     * store the task into tasks table.
     */

    public function store(Request $request)
    {
        $task = Task::create([
            'title' => $request['title'],
            'description' => $request['description'],
        ]);

        return $task;
    }

    /**
     * This function will update the task with task id
     */

    public function update(Request $request)
    {
        $task = Task::findOrFail($request->id);
        $task->title = $request->get('title');
        $task->description = $request->get('description');
        $updated = $task->save();

        return $updated;
    }

    /**
     * This function will delete the task with task id.
     */

    public function destroy($id)
    {
        $deleteTask = Task::findOrFail($id);
        $deleted = $deleteTask->delete();

        return $deleted;
    }
}

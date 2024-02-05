<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; // Ensure the correct namespace is used

class TaskController extends Controller
{
    public function __construct()
    {
        // Adding the 'auth' middleware ensures that only authenticated users can access these methods.
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Retrieve tasks associated with the authenticated user.
        $tasks = Task::where('user_id', $request->user()->id)->get();

        // Return the 'tasks.index' view with the retrieved tasks.
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data.
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        // Create a new task associated with the authenticated user.
        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);

        // Redirect to the '/tasks' route after storing the task.
        return redirect('/tasks');
    }

    public function destroy(Request $request, Task $task)
    {
        // Authorize the authenticated user to delete the task.
        $this->authorize('destroy', $task);

        // Delete the task.
        $task->delete();

        // Redirect to the '/tasks' route after deleting the task.
        return redirect('/tasks');
    }
}

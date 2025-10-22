<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // ✅ Show tasks: project-specific or all user's tasks
    public function index(Project $project = null)
    {
        $user = Auth::user();

        if ($project) {
            // Project-specific tasks
            $tasks = $project->tasks()->get()->groupBy('status');
            $users = $project->users()->get();
            return view('tasks.index', compact('project', 'tasks', 'users'));
        }

        // Global tasks (all tasks of the user across projects)
        $projectIds = $user->projectMembers()->pluck('project_id')->toArray();

        $tasks = Task::where('user_id', $user->id)
            ->orWhereIn('project_id', $projectIds)
            ->latest()
            ->get()
            ->groupBy('status'); // ✅ Group by status for Blade

        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $project->tasks()->create([
            'user_id' => $request->user_id ?? Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => 'to_do', // default new task status
        ]);

        return redirect()
            ->route('projects.tasks.index', $project)
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to_do,in_progress,completed',
        ]);

        $task->update($request->all());

        return redirect()
            ->route('projects.tasks.index', $task->project_id)
            ->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->status = $request->input('status');
        $task->save();

        return response()->json(['message' => 'Task status updated successfully.']);
    }

    // ✅ Delete task
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}

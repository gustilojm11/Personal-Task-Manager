<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::query()
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
            ->orderBy('due_date')
            ->latest()
            ->get();

        return view('welcome', compact('tasks'));
    }

    public function store(Request $request): RedirectResponse
    {
        Task::create($this->validated($request));

        return redirect()->route('tasks.index')->with('success', 'Task added to your list.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($this->validated($request));

        return redirect()->route('tasks.index')->with('success', 'Task details updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task removed.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:Pending,Completed']]);
        $task->update(['status' => $request->input('status')]);

        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'task_name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:Pending,Completed'],
            'due_date' => ['nullable', 'date'],
        ]);
    }
}
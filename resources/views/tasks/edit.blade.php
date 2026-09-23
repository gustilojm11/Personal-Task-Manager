<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit task | Tasklyst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
</head>
<body><main class="shell narrow-shell">
    <header class="topbar"><a class="brand" href="{{ route('tasks.index') }}" aria-label="Tasklyst home"><span class="brand-mark" aria-hidden="true"><span></span></span> Tasklyst</a><a class="back-link" href="{{ route('tasks.index') }}">&larr; Back to tasks</a></header>
    <section class="edit-header"><p class="eyebrow">Task details</p><h1>Edit <em>task.</em></h1></section>
    <form action="{{ route('tasks.update', $task) }}" method="POST" class="edit-form">@csrf @method('PUT')
        <div class="field"><label for="task_name">Task name</label><input id="task_name" name="task_name" value="{{ old('task_name', $task->task_name) }}" required></div>
        <div class="field"><label for="description">Details <span>(optional)</span></label><textarea id="description" name="description" rows="5">{{ old('description', $task->description) }}</textarea></div>
        <div class="form-grid"><div class="field"><label for="status">Status</label><select id="status" name="status"><option {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option><option {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option></select></div><div class="field"><label for="due_date">Due date</label><input id="due_date" type="date" name="due_date" value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"></div></div>
        <button class="primary-button" type="submit">Save changes <span>&rarr;</span></button>
    </form>
</main></body></html>

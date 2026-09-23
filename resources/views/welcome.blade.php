<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasklyst | Personal Task Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
</head>
<body>
    <main class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('tasks.index') }}" aria-label="Tasklyst home"><span class="brand-mark" aria-hidden="true"><span></span></span> Tasklyst</a>
            <span class="date-stamp">{{ now()->format('D / d M Y') }}</span>
        </header>
        <section class="intro">
            <div><p class="eyebrow">Your daily planning desk</p><h1>Do less.<br><em>Finish more.</em></h1><p class="intro-copy">Keep the important work visible, give every task a next step, and move through the day with intention.</p></div>
            <div class="progress-note"><span class="progress-number">{{ $tasks->where('status', 'Completed')->count() }}</span><span>completed<br>of {{ $tasks->count() }}</span></div>
        </section>
        @if (session('success'))<div class="flash">{{ session('success') }} <button onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button></div>@endif
        @if ($errors->any())<div class="flash flash-error">Please check the task details and try again.</div>@endif
        <section class="workspace">
            <div class="section-heading"><div><span class="section-index">01</span><div><h2>Today's focus</h2><p class="section-note">The work still in motion</p></div></div><span class="task-count">{{ str_pad($tasks->count(), 2, '0', STR_PAD_LEFT) }} items</span></div>
            <div class="task-toolbar" aria-label="Task filters">
                <label class="search-field"><span class="search-icon" aria-hidden="true"></span><input id="task-search" type="search" placeholder="Find a task" aria-label="Find a task"></label>
                <label class="filter-field"><span class="sr-only">Filter tasks by status</span><select id="task-filter"><option value="all">All tasks</option><option value="Pending">Pending</option><option value="Completed">Completed</option></select></label>
            </div>
            @forelse ($tasks as $task)
                <article class="task-row {{ $task->status === 'Completed' ? 'is-complete' : '' }}" data-task-row data-status="{{ $task->status }}" data-search="{{ strtolower($task->task_name . ' ' . $task->description) }}">
                    <form action="{{ route('tasks.status', $task) }}" method="POST" class="status-form">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $task->status === 'Pending' ? 'Completed' : 'Pending' }}"><button class="check {{ $task->status === 'Completed' ? 'checked' : '' }}" type="submit" aria-label="Mark {{ $task->task_name }} as {{ $task->status === 'Pending' ? 'completed' : 'pending' }}" title="Mark {{ $task->status === 'Pending' ? 'completed' : 'pending' }}">@if ($task->status === 'Completed') &#10003; @endif</button></form>
                    <div class="task-main"><h3>{{ $task->task_name }}</h3>@if ($task->description)<p>{{ $task->description }}</p>@endif</div>
                    <div class="task-meta"><span class="status {{ strtolower($task->status) }}">{{ $task->status }}</span>@if ($task->due_date)<span class="due-date {{ $task->status === 'Pending' && $task->due_date->isBefore(today()) ? 'overdue' : '' }}">{{ $task->status === 'Pending' && $task->due_date->isBefore(today()) ? 'Overdue' : 'Due' }} {{ $task->due_date->format('d M Y') }}</span>@endif</div>
                    <div class="task-actions"><a class="edit-action" href="{{ route('tasks.edit', $task) }}" aria-label="Edit {{ $task->task_name }}">Edit</a><form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">@csrf @method('DELETE')<button class="delete-action" type="submit" aria-label="Delete {{ $task->task_name }}">Delete</button></form></div>
                </article>
            @empty
                <div class="empty-state"><span>--</span><h3>Nothing competing for your attention.</h3><p>Capture the next thing worth doing below.</p></div>
            @endforelse
            <div class="filtered-empty" data-filtered-empty hidden><h3>No matching tasks</h3><p>Try a different search or status filter.</p></div>
        </section>
        <section class="add-section"><div class="section-heading"><div><span class="section-index">02</span><div><h2>Capture a task</h2><p class="section-note">Get it out of your head</p></div></div></div>
            <form action="{{ route('tasks.store') }}" method="POST" class="task-form">@csrf
                <div class="field field-wide"><label for="task_name">Task name</label><input id="task_name" name="task_name" value="{{ old('task_name') }}" placeholder="What needs doing?" required></div>
                <div class="field"><label for="due_date">Due date</label><input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}"></div>
                <div class="field field-wide"><label for="description">Details <span>(optional)</span></label><textarea id="description" name="description" rows="2" placeholder="Add some context...">{{ old('description') }}</textarea></div>
                <input type="hidden" name="status" value="Pending"><button class="primary-button" type="submit">Add task <span>&rarr;</span></button>
            </form>
        </section>
        <footer>Tasklyst <span>/</span> built for a calmer workflow</footer>
    </main>
    <script>
        const search = document.querySelector('#task-search');
        const filter = document.querySelector('#task-filter');
        const rows = [...document.querySelectorAll('[data-task-row]')];
        const filteredEmpty = document.querySelector('[data-filtered-empty]');

        function filterTasks() {
            const term = search.value.trim().toLowerCase();
            const status = filter.value;
            let visible = 0;

            rows.forEach((row) => {
                const matches = (!term || row.dataset.search.includes(term)) && (status === 'all' || row.dataset.status === status);
                row.hidden = !matches;
                if (matches) visible += 1;
            });

            filteredEmpty.hidden = visible !== 0 || rows.length === 0;
        }

        search.addEventListener('input', filterTasks);
        filter.addEventListener('change', filterTasks);
    </script>
</body>
</html>

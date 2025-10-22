@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Fade-in animation */
    .fade-in {
        animation: fadeIn 0.8s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Card hover lift effect */
    .card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Button hover effect */
    .btn-primary {
        transition: all 0.3s ease;
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    /* Card titles style */
    .card-title {
        font-weight: 600;
        color: #333;
    }

    .card:hover .card-title {
        color: #007bff;
    }

    .list-group-item {
        transition: background-color 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .empty-state {
        color: #999;
        text-align: center;
        padding: 10px 0;
    }
</style>

<div class="container fade-in">
    <h2 class="mb-4 fw-bold">Welcome to your Dashboard {{ Auth::user()->name }}</h2>
    <p class="text-muted mb-4">Manage your tasks, routines, notes, and files efficiently in one place.</p>

    <div class="row mb-4">
        <!-- Dashboard Cards -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-check2-square fs-1 text-primary mb-3"></i>
                    <h5 class="card-title">Tasks</h5>
                    <p class="card-text flex-grow-1">You have <strong>{{ $tasksCount }}</strong> tasks pending.</p>
                    <!-- FIXED LINK -->
                    <a href="{{ route('projects.index') }}" class="btn btn-primary mt-auto">View Tasks</a>

                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-calendar-check fs-1 text-success mb-3"></i>
                    <h5 class="card-title">Routines</h5>
                    <p class="card-text flex-grow-1">You have <strong>{{ $routinesCount }}</strong> routines today.</p>
                    <a href="{{ route('routines.index') }}" class="btn btn-primary mt-auto">View Routines</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-sticky fs-1 text-warning mb-3"></i>
                    <h5 class="card-title">Notes</h5>
                    <p class="card-text flex-grow-1">You have <strong>{{ $notesCount }}</strong> notes saved.</p>
                    <a href="{{ route('notes.index') }}" class="btn btn-primary mt-auto">View Notes</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-file-earmark fs-1 text-danger mb-3"></i>
                    <h5 class="card-title">Files</h5>
                    <p class="card-text flex-grow-1">You have <strong>{{ $filesCount }}</strong> files.</p>
                    <a href="{{ route('files.index') }}" class="btn btn-primary mt-auto">View Files</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Recent Tasks -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="bi bi-list-task text-primary me-2"></i>Recent Tasks</h5>
                    <ul class="list-group flex-grow-1">
                        @forelse($recentTasks as $task)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $task->title }}
                                <span class="badge bg-{{ $task->status_color ?? 'secondary' }} rounded-pill">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item empty-state">No recent tasks found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Today's Routines -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="bi bi-calendar-event text-success me-2"></i>Today's Routines</h5>
                    <ul class="list-group flex-grow-1">
                        @forelse($todayRoutines as $routine)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $routine->title }}
                                <span class="badge bg-success rounded-pill">{{ $routine->frequency }}</span>
                            </li>
                        @empty
                            <li class="list-group-item empty-state">No routines for today.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Notes -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="bi bi-journal-text text-warning me-2"></i>Recent Notes</h5>
                    <ul class="list-group flex-grow-1">
                        @forelse($recentNotes as $note)
                            <li class="list-group-item">{{ $note->title }}</li>
                        @empty
                            <li class="list-group-item empty-state">No notes available.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Upcoming Reminders -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="bi bi-bell text-danger me-2"></i>Upcoming Reminders</h5>
                    <ul class="list-group flex-grow-1">
                        @forelse($upcomingReminders as $reminder)
                            <li class="list-group-item d-flex justify-content-between align-items-center 
                                {{ $reminder->date->isToday() ? 'bg-warning' : ($reminder->date->isPast() ? 'bg-danger text-white' : 'bg-success text-white') }}">
                                {{ $reminder->title }}
                                <span class="badge bg-dark rounded-pill">
                                    {{ $reminder->date->format('M d') }} {{ $reminder->time ? $reminder->time->format('H:i') : '' }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item empty-state">No upcoming reminders.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.card').forEach((card, i) => {
            card.style.animationDelay = `${i * 0.1}s`;
            card.classList.add('fade-in');
        });
    });
</script>
@endsection

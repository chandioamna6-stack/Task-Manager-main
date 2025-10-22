<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/clear-welcome-flag', function () {
    session()->forget('show_welcome');
    return response()->json(['status' => 'cleared']);
});

Route::middleware(['auth'])->group(function () {

    // Mail routes
    Route::controller(MailController::class)->prefix('mail')->name('mail.')->group(function () {
        Route::get('/', 'index')->name('inbox');
    });

    // Projects and Project Team
    Route::resource('projects', ProjectController::class);
    Route::post('project/team', [ProjectController::class, 'addMember'])->name('projects.addMember');

    // Tasks routes
    Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('tasks/{task}/update-status', [TaskController::class, 'updateStatus']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // ✅ Global tasks route for dashboard & sidebar
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');

    // Routines
    Route::resource('routines', RoutineController::class)->except(['show']);
    Route::get('routines/showAll', [RoutineController::class, 'showAll'])->name('routines.showAll');
    Route::get('routines/daily', [RoutineController::class, 'showDaily'])->name('routines.showDaily');
    Route::get('routines/weekly', [RoutineController::class, 'showWeekly'])->name('routines.showWeekly');
    Route::get('routines/monthly', [RoutineController::class, 'showMonthly'])->name('routines.showMonthly');

    // Files, Notes, Reminders, Checklist Items
    Route::resource('files', FileController::class);
    Route::resource('notes', NoteController::class);
    Route::resource('reminders', ReminderController::class);
    Route::resource('checklist-items', ChecklistItemController::class);
    Route::get('checklist-items/{checklistItem}/update-status', [ChecklistItemController::class, 'updateStatus'])->name('checklist-items.update-status');

    // Dashboard route
    Route::get('/', function () {
        $user = Auth::user();

        // ✅ Get IDs of projects where the user is a member
        $projectIds = $user->projectMembers()->pluck('project_id')->toArray();

        // ✅ Count tasks assigned to user OR in user's projects
        $tasksQuery = Task::where('user_id', $user->id);
        if (!empty($projectIds)) {
            $tasksQuery->orWhereIn('project_id', $projectIds);
        }
        $tasksCount = $tasksQuery->count();

        // ✅ Fetch recent tasks (same logic)
        $recentTasksQuery = Task::where('user_id', $user->id);
        if (!empty($projectIds)) {
            $recentTasksQuery->orWhereIn('project_id', $projectIds);
        }
        $recentTasks = $recentTasksQuery->latest()->take(5)->get();

        // ✅ Counts for other items
        $routinesCount = $user->routines()->count();
        $notesCount = $user->notes()->count();
        $remindersCount = $user->reminders()->count();
        $filesCount = $user->files()->count();

        // ✅ Today's date info
        $today = now();
        $dayName = strtolower($today->format('l'));
        $weekOfYear = $today->weekOfYear;
        $month = $today->month;

        // ✅ Fetch today's routines based on frequency
        $todayRoutines = $user->routines()
            ->where(function($query) use ($dayName, $weekOfYear, $month) {
                $query->where(function($q) use ($dayName) {
                    $q->where('frequency', 'daily')
                      ->whereJsonContains('days', $dayName);
                })
                ->orWhere(function($q) use ($weekOfYear) {
                    $q->where('frequency', 'weekly')
                      ->whereJsonContains('weeks', $weekOfYear);
                })
                ->orWhere(function($q) use ($month) {
                    $q->where('frequency', 'monthly')
                      ->whereJsonContains('months', $month);
                });
            })
            ->get();

        // ✅ Fetch recent notes, upcoming reminders
        $recentNotes = $user->notes()->latest()->take(5)->get();
        $upcomingReminders = $user->reminders()
                                  ->where('date', '>=', now())
                                  ->orderBy('date')
                                  ->take(5)
                                  ->get();

        return view('dashboard', compact(
            'tasksCount',
            'routinesCount',
            'notesCount',
            'remindersCount',
            'filesCount',
            'recentTasks',
            'todayRoutines',
            'recentNotes',
            'upcomingReminders'
        ));
    })->name('dashboard');

});

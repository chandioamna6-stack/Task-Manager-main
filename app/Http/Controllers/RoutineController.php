<?php
namespace App\Http\Controllers;

use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RoutineController extends Controller
{
    // --- DASHBOARD: Get today's routines ---
    public function getTodayRoutines()
    {
        $today = Carbon::today();
        $dayName = strtolower($today->format('l'));
        $weekOfYear = $today->weekOfYear;
        $month = $today->month;

        // Daily routines for today
        $dailyRoutines = Auth::user()->routines()
            ->where('frequency', 'daily')
            ->whereJsonContains('days', $dayName)
            ->get();

        // Weekly routines for this week
        $weeklyRoutines = Auth::user()->routines()
            ->where('frequency', 'weekly')
            ->whereJsonContains('weeks', $weekOfYear)
            ->get();

        // Monthly routines for this month
        $monthlyRoutines = Auth::user()->routines()
            ->where('frequency', 'monthly')
            ->whereJsonContains('months', $month)
            ->get();

        // Merge all routines into a single collection
        return $dailyRoutines->merge($weeklyRoutines)->merge($monthlyRoutines);
    }

    // --- Index page: upcoming routines ---
    public function index()
    {
        $today = Carbon::today();
        $dayName = strtolower($today->format('l'));
        $weekOfYear = (string) $today->weekOfYear;
        $month = (string) $today->month;

        $upcomingDailyRoutines = Auth::user()->routines()
            ->where('frequency', 'daily')
            ->whereJsonContains('days', $dayName)
            ->take(2)
            ->get();

        $upcomingWeeklyRoutines = Auth::user()->routines()
            ->where('frequency', 'weekly')
            ->take(2)
            ->get();

        $upcomingMonthlyRoutines = Auth::user()->routines()
            ->where('frequency', 'monthly')
            ->take(2)
            ->get();

        return view('routines.index', compact(
            'upcomingDailyRoutines',
            'upcomingWeeklyRoutines',
            'upcomingMonthlyRoutines'
        ));
    }

    public function create()
    {
        return view('routines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
            'days' => 'nullable|array',
            'weeks' => 'nullable|array',
            'months' => 'nullable|array',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $routineData = $request->all();

        if ($request->has('days')) {
            $routineData['days'] = json_encode($request->days);
        }
        if ($request->has('weeks')) {
            $routineData['weeks'] = json_encode($request->weeks);
        }
        if ($request->has('months')) {
            $routineData['months'] = json_encode($request->months);
        }

        Auth::user()->routines()->create($routineData);

        return redirect()->route('routines.index')->with('success', 'Routine created successfully.');
    }

    public function edit(Routine $routine)
    {
        return view('routines.edit', compact('routine'));
    }

    public function update(Request $request, Routine $routine)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
            'days' => 'nullable|array',
            'weeks' => 'nullable|array',
            'months' => 'nullable|array',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $routineData = $request->all();

        if ($request->has('days')) {
            $routineData['days'] = json_encode($request->days);
        }
        if ($request->has('weeks')) {
            $routineData['weeks'] = json_encode($request->weeks);
        }
        if ($request->has('months')) {
            $routineData['months'] = json_encode($request->months);
        }

        $routine->update($routineData);

        return redirect()->route('routines.index')->with('success', 'Routine updated successfully.');
    }

    public function destroy(Routine $routine)
    {
        $routine->delete();
        return redirect()->route('routines.index')->with('success', 'Routine deleted successfully.');
    }

    public function showAll()
    {
        $dailyRoutines = Auth::user()->routines()->where('frequency', 'daily')->get();
        $weeklyRoutines = Auth::user()->routines()->where('frequency', 'weekly')->get();
        $monthlyRoutines = Auth::user()->routines()->where('frequency', 'monthly')->get();

        return view('routines.all', compact('dailyRoutines', 'weeklyRoutines', 'monthlyRoutines'));
    }

    public function showDaily()
    {
        $dailyRoutines = Auth::user()->routines()->where('frequency', 'daily')->get();
        return view('routines.daily', compact('dailyRoutines'));
    }

    public function showWeekly()
    {
        $weeklyRoutines = Auth::user()->routines()->where('frequency', 'weekly')->get();
        return view('routines.weekly', compact('weeklyRoutines'));
    }

    public function showMonthly()
    {
        $monthlyRoutines = Auth::user()->routines()->where('frequency', 'monthly')->get();
        return view('routines.monthly', compact('monthlyRoutines'));
    }
}

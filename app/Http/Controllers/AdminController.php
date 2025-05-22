<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAllJobs()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $jobs = Job::with(['employer', 'category', 'applications'])->get();

        return response()->json([
            'data' => $jobs
        ]);
    }

    public function getAllUsers()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::with(['applications'])->get();

        return response()->json([
            'data' => $users
        ]);
    }

    public function getPlatformStats()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $stats = [
            'total_jobs' => Job::count(),
            'total_users' => User::count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_candidates' => User::where('role', 'candidate')->count(),
            'total_applications' => Application::count(),
            'pending_jobs' => Job::where('status', 'pending')->count(),
            'approved_jobs' => Job::where('status', 'approved')->count(),
            'rejected_jobs' => Job::where('status', 'rejected')->count(),
        ];

        return response()->json([
            'data' => $stats
        ]);
    }
public function disableUser($id)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->update(['is_active' => false]);

    return response()->json([
        'message' => 'User disabled successfully',
        'data' => $user
    ]);
}

public function deleteUser($id)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->delete();

    return response()->json([
        'message' => 'User deleted successfully'
    ]);
}

public function search(Request $request)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $query = User::query();

    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('email')) {
        $query->where('email', 'like', '%' . $request->email . '%');
    }

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active);
    }

    $users = $query->get();

    return response()->json([
        'data' => $users
    ]);
} 
}
<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, $jobId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized: User not found'], 401);
        }

        $comment = Comment::create([
            'job_id' => $jobId,
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => $comment
        ], 201);
    }

    public function index($jobId)
    {
        $comments = Comment::where('job_id', $jobId)
                    ->with('user:id,name')
                    ->get();

        return response()->json([
            'data' => $comments
        ]);
    }

    public function indexForUser($jobId, $userId)
    {
        $comments = Comment::where('job_id', $jobId)
                    ->where('user_id', $userId)
                    ->with('user:id,name')
                    ->get();

        return response()->json([
            'data' => $comments
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $user = auth()->user();

        if ($user->id !== $comment->user_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

public function adminDestroy($id)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $comment = Comment::find($id);

    if (!$comment) {
        return response()->json(['message' => 'Comment not found'], 404);
    }

    $comment->delete();

    return response()->json(['message' => 'Comment deleted by admin successfully']);
}
}


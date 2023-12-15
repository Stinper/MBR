<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Topic;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CommentController extends Controller
{
    public function list()
    {
        try {
            $this->authorize('view_comments_list');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        if(Auth::user()->can('view_restricted_comment')) {
            $comments = Comment::all();
        }
        else {
            $comments = Comment::where('is_restricted', false)->get();
        }

        return response()->json($comments);
    }

    public function retrieve(Comment $comment)
    {
        try {
            if ($comment->is_restricted) {
                $this->authorize('view_restricted_comment');
            }

            $this->authorize('view_comment');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        return response()->json($comment);
    }

    public function create(Request $request)
    {
        try {
            $target_topic = Topic::find($request->topic_id);

            if($target_topic->is_closed) {
              $this->authorize('create_comment_in_closed_topic');
            }

            if($target_topic->is_restricted) {
                $this->authorize('create_comment_in_restricted_topic');
            }

            $this->authorize('create_comment');

        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'topic_id' => 'required',
            'is_restricted' => 'boolean',
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $comment = new Comment($request->all());
        $comment->creator_id = Auth::id();
        $comment->save();
        return response()->json($comment, 201);
    }

    public function update(Request $request, Comment $comment)
    {
        try {
            if ($comment->creator_id != Auth::id()) {
                $this->authorize('update_not_own_comment');
            }

            $this->authorize('update_comment');

        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'topic_id' => 'required',
            'is_restricted' => 'boolean',
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $comment->update($request->all());
        return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        try {
            if ($comment->creator_id != Auth::id()) {
                $this->authorize('delete_not_own_comment');
            }

            $this->authorize('delete_comment');

        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $comment->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CommentController extends Controller
{
    public function list()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }

    public function retrieve(Comment $comment)
    {
        return response()->json($comment);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'topic_id' => 'required',
            'is_restricted' => 'boolean',
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $topic = new Topic($request->all());
        $topic->creator_ic = Auth::id();
        $topic->save();
        return response()->json($topic, 201);
    }

    public function update(Request $request, Topic $topic)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'topic_id' => 'required',
            'is_restricted' => 'boolean',
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $topic->update($request->all());
        return response()->json($topic);
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return response()->json(null, 204);
    }
}

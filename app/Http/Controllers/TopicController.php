<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    public function list()
    {
        $topics = Topic::all();
        return response()->json($topics);
    }

    public function retrieve(Topic $topic)
    {
        return response()->json($topic);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header' => 'required|string',
            'body' => 'required|string',
            'section_id' => 'required',
            'is_restricted' => 'boolean',
            'is_pinned' => 'boolean',
            'is_closed' => 'boolean',
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $topic = new Topic($request->all());
        $topic->creator_id = Auth::id();
        $topic->save();

        return response()->json($topic, 201);
    }

    public function update(Request $request, Topic $topic)
    {
        $validator = Validator::make($request->all(), [
            'header' => 'required|string',
            'body' => 'required|string',
            'section_id' => 'required',
            'is_restricted' => 'boolean',
            'is_pinned' => 'boolean',
            'is_closed' => 'boolean',
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

<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    public function list()
    {
        try {
            $this->authorize('view_topics_list');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        if (Auth::user()->can('view_restricted_topic')) {
            $topics = Topic::all();
        }
        else {
            $topics = Topic::where('is_restricted', false)->get();
        }

        return response()->json($topics);
    }

    public function retrieve(Topic $topic)
    {
        try {
            if ($topic->is_restricted) {
                $this->authorize('view_restricted_topic');
            }

            $this->authorize('view_topic');

        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        return response()->json($topic);
    }

    public function create(Request $request)
    {
        try {
            $this->authorize('create_topic');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

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
        try {
            if ($topic->creator_id != Auth::id()) {
                $this->authorize('update_not_own_topic');
            }

            $this->authorize('update_topic');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

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
        try {
            if ($topic->creator_id != Auth::id()) {
                $this->authorize('delete_not_own_topic');
            }

            $this->authorize('delete_topic');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $topic->delete();
        return response()->json(null, 204);
    }

    public function close(Topic $topic)
    {
        try {
            $this->authorize('close_topic');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $topic->is_closed = true;
        return response()->json($topic);
    }

    public function open(Topic $topic)
    {
        try {
            $this->authorize('close_topic');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $topic->is_closed = false;
        return response()->json($topic);
    }

}

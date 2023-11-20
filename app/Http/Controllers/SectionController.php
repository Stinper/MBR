<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function list()
    {
        $sections = Section::all();
        return response()->json($sections);
    }

    public function retrieve(Section $section)
    {
        return response()->json($section);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category_id' => 'required',
            'is_restricted' => 'required|boolean'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $section = new Section($request->all());
        $section->creator_id = Auth::id();
        $section->save();

        return response()->json($section, 201);
    }

    public function update(Request $request, Section $section)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category_id' => 'required',
            'is_restricted' => 'required|boolean'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $section->update($request->all());
        return response()->json($section);
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return response()->json(null, 204);
    }
}

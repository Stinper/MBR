<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function list()
    {
        try {
            $this->authorize('view_categories_list');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        if (Auth::user()->can('view_restricted_category')) {
            $categories = Category::all();
        }
        else {
            $categories = Category::where('is_restricted', false)->get();
        }

        return response()->json($categories);
    }

    public function retrieve(Category $category)
    {
        try {
            $this->authorize('view_category');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        return response()->json($category);
    }

    public function create(Request $request)
    {
        try {
            $this->authorize('create_category');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'is_restricted' => 'required|boolean'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $category = new Category($request->all());
        $category->creator_id = Auth::id();
        $category->save();

        return response()->json($category, 201);
    }

    public function update(Request $request, Category $category)
    {
        try {
            $this->authorize('update_category');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'is_restricted' => 'required|boolean'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }

        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        try {
            $this->authorize('delete_category');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}

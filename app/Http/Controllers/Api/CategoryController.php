<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use HttpResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = CategoryResource::collection(
            Category::when($request->q, function ($query) use ($request) {
                            $query->where('name', 'like', "%{$request->q}%");
                        })->paginate(3));

        return $this->successResponse("retrieved", $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()) {
            return $this->failResponse("Validation failed to store category.", $validator->messages());
        }

        $category = Category::create([
            'name' => $request->name
        ]);

        return $this->successResponse("created", $category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = new CategoryResource(Category::find($id));

        if($category) {
            return $this->successResponse("retrieved", $category);
        } else {
            return $this->failResponse("Item not found", null, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()) {
            return $this->failResponse("Validation failed to upate.", $validator->messages());
        }

        $category = Category::find($id);
        $category->update([
            'name' => $request->name
        ]);
        return $this->successResponse("updated", $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if($category) {
            return $this->successResponse("deleted", null);
        } else {
            return $this->failResponse("Item not found", null, 404);
        }
    }
}

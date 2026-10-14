<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    use HttpResponseTrait;

    public function index (Request $request) {
        
        $blogs = BlogResource::collection(
            Blog::when($request->q, function ($query) use ($request) {
                            $query->where(function ($q) use ($request) {
                                $q->where('title', 'like', "%{$request->q}%")
                                ->orWhere('body', 'like', "%{$request->q}%");
                            });
                        })->paginate());

        return $this->successResponse("retrieved", $blogs);
        // return $blogs;
        // return response()->json([
        //     "message" => "Successfully retrieved.",
        //     "status" => "passed",
        //     "data" => $blogs
        // ], 200);
    }

    public function show($id) {
        $blog = new BlogResource(Blog::find($id));

        return $this->successResponse("retrieved", $blog);
        // return response()->json([
        //     "message" => "Successfully retrieved.",
        //     "status" => "passed",
        //     "data" => $blog
        // ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'title' => 'required',
            'body' => 'required'
        ]);

        if($validator->fails()) {
            return $this->failResponse("Validation failed.", $validator->messages());
            // return response()->json([
            //     "message" => "Validation failed.",
            //     "status" => "Failed",
            //     "data" => $validator->messages()
            // ], 500);
        }

        $blog = Blog::create($validator->validated());

        return $this->successResponse("created", $blog, 201);
        // return response()->json([
        //     "message" => "Successfully stored.",
        //     "status" => "passed",
        //     "data" => $blog
        // ], 201);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required'
        ]);
        
        if($validator->fails()) {
            return $this->failResponse("Validation failed.", $validator->messages());
        }
        
        $blog = Blog::find($id);
        $blog->update($validator->validated());

        return $this->successResponse("updated", $blog);
        // return response()->json([
        //     "message" => "Successfully updated.",
        //     "status" => "Success",
        //     "data" => $blog
        // ], 200);
    }

    public function destroy($id) {
        $blog = Blog::find($id);

        if ($blog) {
            $blog->delete();
            return $this->successResponse("deleted", null);
            // return response()->json([
            //     "message" => "Successfully deleted.",
            //     "status" => "passed",
            //     "data" => null
            // ]);
        } else {
            return $this->failResponse("Item not found.", null);
            // return response()->json([
            //     "message" => "Item not found."
            // ]);
        }
    }
}

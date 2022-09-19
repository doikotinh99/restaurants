<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * @OA\Tag(
 *     name="Blog",
 *     description="full api"
 * )
*/
class BlogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blog",
     *     tags={"Blog"},
     *     summary="get all Blog",
     *     operationId="Blog",
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *
     * )
     */
    public function index()
    {
        //
        $result = Blog::all();
        foreach($result as $val){
            $val->comments;
            foreach($val->comments as $cmt){
                $cmt->childComment;
                
            }
        }
        
        return $result;
    }

    /**
     * @OA\Post(
     *     path="/api/blog",
     *     tags={"Blog"},
     *     summary="add Blog",
     *     operationId="addBlog",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="content",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                  property="image[]",
     *                  type="array",
     *                  @OA\Items(
     *                       type="string",
     *                       format="binary",
     *                  ),
     *               )
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function store(Request $request)
    {
        //
        $result = Blog::create([
            "user_id" => $request->user()->id,
            "content" => $request->content,
            "status" => 1
        ]);

        if ($request->image) {
            $files = $request->image;
            foreach ($files as $key => $file) {
                $images = $file->store("public/blogs");
                // $filename = $images->name;
                $filename = explode("/", $images)[2];
                $image = new Image([
                    "path" => "blogs/".$filename
                ]);
                $result->images()->save($image);
            }
        }
        return response()->json(["blog" => $result], 200);
    }

    /**
     * @OA\get(
     *     path="/api/blog/{id}",
     *     tags={"Blog"},
     *     summary="detail Blog",
     *     operationId="detailBlog",
     *     @OA\Parameter(
     *         name="id",
     *         description="blog id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     * 
     * )
     */
    public function show(Blog $blog)
    {
        $blog->comments;
        return response()->json(["blog" => $blog], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/blog/{id}",
     *     tags={"Blog"},
     *     summary="add Blog",
     *     operationId="updateBlog",
     *     @OA\Parameter(
     *         name="id",
     *         description="blog id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="content",
     *         description="content",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function update(Request $request, Blog $blog)
    {
        //
        if($request->user()->id != $blog->user_id)
            return response()->json(["msg" => "false user"], 404);
        $result = $blog->update([
            "content" => $request->content
        ]);
        return response()->json(["blog" => $result], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/blog/{id}",
     *     tags={"Blog"},
     *     summary="delete Blog",
     *     operationId="deleteBlog",
     *     @OA\Parameter(
     *         name="id",
     *         description="blog id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function destroy(Blog $blog)
    {
        //
        if(Auth::user()->id != $blog->user_id)
            return response()->json(["msg" => "false"], 404);
        $blog->delete();
        $blog->comments()->delete();
        return response()->json(["blog" => "deleted"], 200);
    }
    /**
     * @OA\Post(
     *     path="/api/blog/cmt",
     *     tags={"Blog"},
     *     summary="add Blog",
     *     operationId="cmtBlog",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="blog_id",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="content",
     *                  type="text"
     *               ),
     *               
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success with some route data"
     *     ),
     *     security={{"bearer":{}}},
     * )
     */
    public function cmt(Request $request){
        $blog = Blog::find($request->blog_id);
        $cmt = new Comment([
            "user_id" => $request->user()->id,
            "content" => $request->content,
        ]);
        $result = $blog->comments()->save($cmt);
        return response()->json(["cmt" => $result], 200);
    }
}

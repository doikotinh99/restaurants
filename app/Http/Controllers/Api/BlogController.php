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
        foreach ($result as $val) {
            $val->comments;
            foreach ($val->comments as $cmt) {
                $cmt->repComment;
            }
            $val->images;
            $val->user;
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
     *                  property="title",
     *                  type="text"
     *               ),
     *              @OA\Property(
     *                  property="description",
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
            "title" => $request->title,
            "description" => $request->description,
            "status" => 1
        ]);

        if ($request->image) {
            $files = $request->image;
            foreach ($files as $key => $file) {
                // $images = $file->store("public/blogs");
                
                // $filename = $images->name;
                $ext = $file->extension();
                $fileName = 'blog-' . time() . '.' . $ext;
                $file->move(public_path("images/blogs"), $fileName);
                $image = new Image([
                    "path" => "blogs/" . $fileName
                ]);
                $result->images()->save($image);
            }
        }
        return $result;
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
        $blog->images;
        $blog->user;
        return $blog;
    }

    /**
     * @OA\Put(
     *     path="/api/blog/{id}",
     *     tags={"Blog"},
     *     summary="edit Blog",
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
        if ($request->user()->id != $blog->user_id)
            return response()->json(["msg" => "false user"], 404);
        $result = $blog->update([
            "content" => $request->content
        ]);
        return $result;
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
        if (Auth::user()->id != $blog->user_id)
            return response()->json(["msg" => "false"], 404);
        $blog->delete();
        $blog->comments()->delete();
        return response()->json(["blog" => "deleted"], 200);
    }
    /**
     * @OA\Post(
     *     path="/api/blog/cmt",
     *     tags={"Blog"},
     *     summary="comment Blog",
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
    public function cmt(Request $request)
    {
        $blog = Blog::find($request->blog_id);
        $cmt = new Comment([
            "user_id" => $request->user()->id,
            "content" => $request->content,
        ]);
        $result = $blog->comments()->save($cmt);
        return $result;
    }
    /**
     * @OA\Post(
     *     path="/api/blog/repcmt",
     *     tags={"Blog"},
     *     summary="Ceply Comment Blog",
     *     operationId="repCmtBlog",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="cmt_id",
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
    public function replyComment(Request $request)
    {
        $cmt = Comment::find($request->cmt_id);
        $repCmt = new Comment([
            "user_id" => $request->user()->id,
            "content" => $request->content,
        ]);
        $result = $cmt->repComment()->save($repCmt);
        return $result;
    }
}

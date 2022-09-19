<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admin/blog/status/{id}",
     *     operationId="statusBlog",
     *     tags={"Blog"},
     *     summary="status Blog",
     *     description="status Blog",
     *     @OA\Parameter(
     *         name="id",
     *         description="Blog id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *      security={{"bearer":{}}},
     *)
     */
    public function status(Blog $blog)
    {
        $status = !$blog->status;
        Blog::where("id", $blog->id)->update([
            "status" => (int)$status
        ]);
        return response()->json(["blog" => $blog, "status" => "$status"], 200);
    }
}

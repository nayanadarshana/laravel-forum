<?php

namespace App\Repositories\Post;


use App\Interfaces\Post\PostInterface;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostRepository implements PostInterface
{

    public function index(Request $request)
    {
        try {
            $query = [];
            if (Auth::user()->hasRole('Admin')) {
                $query = Post::whereIn('status', [0, 1, 2]);
            } else {
                $query = Post::where('status', 1)->orWhere('user_id', Auth::user()->id);
            }
            return $query->get()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'user' => $item->user,
                    'title' => $item->title,
                    'content' => $item->content,
                    'status' => $item->getStatusNameAttribute(),
                    'approved_by' => $item->approvedBy,
                    'created_time' => $item->created_at
                ];
            });
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }

    }

    public function store(Request $request)
    {
        try {
            $postObj = new Post();
            $postObj->user_id = Auth::user()->id;
            $postObj->title = $request->title;
            $postObj->content = $request->post_content;
            $postObj->status = Auth::user()->hasRole('Admin') ? 1 : 0;
            $postObj->save();
            return $postObj->id;
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }
    }

    public function delete($id)
    {
        try {
            Post::find($id)->delete();
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }

    }

    public function approvePost(Request $request, $id)
    {
        try {
            $postObj = Post::find($id);
            $postObj->status = $request->status == "APPROVE" ? 1 : 2;
            $postObj->approved_by = Auth::user()->id;
            $postObj->save();
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }

    }

    public function comment(Request $request, $postId)
    {
        try {
            $obj = new Comment();
            $obj->post_id = $postId;
            $obj->user_id = Auth::user()->id;
            $obj->content = $request->comment_content;
            $obj->save();
            return $obj->id;
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }
    }

    public function edit($id)
    {
        try {
            $post = Post::where('id', $id)->first();
            return [
                'id' => $post->id,
                'user' => $post->user,
                'title' => $post->title,
                'content' => $post->content,
                'status' => $post->getStatusNameAttribute(),
                'created_time' => $post->created_at,
            ];
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }
    }

    public function getSelectedPostData($id)
    {
        try {
            return Post::find($id);
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }
    }
}

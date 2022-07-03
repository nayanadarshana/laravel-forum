<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidatePostApproveRequest;
use App\Http\Requests\ValidatePostRequest;
use App\Interfaces\Post\PostInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    private PostInterface $postInterface;

    public function __construct(PostInterface $postInterface)
    {
        $this->postInterface = $postInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $data = $this->postInterface->index($request);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(ValidatePostRequest $request)
    {
        try {
            $request->validated();
            $data = $this->postInterface->store($request);
            return response()->json(['id' => $data], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        try {
            $data = $this->postInterface->edit($id);
            return response()->json($data, 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $post = $this->postInterface->getSelectedPostData($id);
            if ($post->user_id == Auth::user()->id) {
                $this->postInterface->delete($id);
            } else {
                return response()->json('User does not have authority', 403);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function approvePost(ValidatePostApproveRequest $request, $id)
    {
        try {
            if (Auth::user()->hasRole('Admin')) {
                $request->validated();
                $this->postInterface->approvePost($request, $id);
                return response()->json('Successfully Approved', 200);
            } else {
                return response()->json('User does not have authority', 403);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function comment(Request $request, $postId)
    {
        try {
            $data = $this->postInterface->comment($request, $postId);
            return response()->json(['id' => $data], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getComments($id)
    {
        try {
            return response()->json([]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

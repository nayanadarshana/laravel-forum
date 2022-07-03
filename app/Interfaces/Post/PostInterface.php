<?php

namespace App\Interfaces\Post;

use Illuminate\Http\Request;

interface PostInterface
{
    public function index(Request $request);

    public function store(Request $request);

    public function delete($id);

    public function edit($id);

    public function approvePost(Request $request, $id);

    public function comment(Request $request, $postId);

    public function getSelectedPostData($id);
}

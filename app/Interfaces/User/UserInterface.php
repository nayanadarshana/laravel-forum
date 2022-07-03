<?php

namespace App\Interfaces\User;

interface UserInterface
{
    public function store(\Illuminate\Http\Request $request);
    public function getUser($id);
}

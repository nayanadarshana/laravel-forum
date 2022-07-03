<?php

namespace App\Repositories\User;

use App\Interfaces\User\UserInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserRepository implements UserInterface
{

    public function store(Request $request): User
    {
        try {
            $userObj = new User();
            $userObj->name = $request['name'];
            $userObj->email = $request['email'];
            $userObj->password = bcrypt($request['password']);
            $userObj->save();
            $userObj->assignRole('User');
            return $userObj;
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }
    }

    public function getUser($id)
    {
        try {
            return User::find($id);
        } catch (Exception $e) {
            throw new Exception($e);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException();
        }
    }
}

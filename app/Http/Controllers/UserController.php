<?php

namespace App\Http\Controllers;


use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Interfaces\User\UserInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    private UserInterface $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $request->validated();
            $user = $this->userInterface->store($request);
            if ($user) {
                $role = $user->roles->pluck('name');
                $token = $user->createToken('Token name');
                $userData = $this->userInterface->getUser($user->id);
                return response()->json([
                    'accessToken' => $token->accessToken,
                    'user' => $userData,
                    'role' => $role[0]
                ]);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('Token name');
                $role = Auth::user()->roles->pluck('name');
                $userData = $this->userInterface->getUser(Auth::user()->id);
                return response()->json([
                    'accessToken' => $token->accessToken,
                    'user' => $userData,
                    'role' => $role[0]
                ]);
            } else {
                return response()->json("Credential not much", 401);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function validateUser(): JsonResponse
    {
        try {
            $role = Auth::user()->roles->pluck('name');
            $status = Auth::guard('api')->check();
            if ($status) {
                $user = $this->userInterface->getUser(Auth::user()->id);
                $data = [
                    'user' => $user,
                    'role' => $role[0]
                ];
                return response()->json($data, 200);
            } else {
                return response()->json(['data' => ''], 403);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $token = $request->user()->token();
            $token->revoke();
            return response()->json('Successfully Logout', 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/user",
     *     summary="Get authenicated user information",
     *     tags={"Users"},
     *     @OA\Response(response=200, description="User information retrieved"),
     * )
     */
    function info(){
        $user = Auth::user();
        return response()->json([
            'name' => $user->name,
            'token' => $user->token,
            'email' => $user->email,
            'username' => $user->username,
        ], 200);
    }

    /**
     * @OA\Get (
     *     path="/api/user/list",
     *     summary="Retrieve user task list",
     *     tags={"User"},
     *     @OA\Response(response=200, description="List retrieved")
     * )
     */
    function list() {
        $list = Auth::user()->todoList;
        return response()->json($list, 200);
    }
}

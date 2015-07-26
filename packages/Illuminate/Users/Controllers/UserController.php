<?php

namespace PhpSoft\Illuminate\Users\Controllers;

use Auth;
use JWTAuth;
use Validator;
use Illuminate\Http\Request;
use PhpSoft\Illuminate\Users\Models\User;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function authenticated()
    {
        if (!$this->checkAuth()) {
            return response()->json(null, 401);
        }

        return response()->json(arrayView('user/read', [
            'user' => Auth::user()
        ]), 200);
    }

    /**
     * Register action
     *
     * @param  Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:255',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(arrayView('errors/validation', [
                'errors' => $validator->errors()
            ]), 400);
        }

        $user = User::create($request->all());

        return response()->json(arrayView('user/read', [
            'user' => $user
        ]), 201);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\PostResource;

use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $users = User::latest()->paginate(5);

        return new PostResource(true, 'List 5 latest Users', $users);
    }

    public function store(Request $request) {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // check if user exist
        $user = User::where('email', $request->email)
            ->first();
        if ($user) {
            return response()->json([
                'email' => [
                    'User email already exists'
                ]
            ], 422);
        }

        //create user
        $user = User::create([
            'email' => $request->email,
        ]);

        //return response
        return new PostResource(true, 'User added!', $user);
    }
}

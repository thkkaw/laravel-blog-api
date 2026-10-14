<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use HttpResponseTrait;
    /**
     * Register
    */
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return $this->failResponse("Validation failed.", $validator->messages());
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password
        ]);
        return $this->successResponse("registered", $user, 201);
    }    

    /**
     * Login
    */
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->failResponse("User validation failed.", $validator->messages());
        }

        if (Auth::attempt($validator->validated())) {
            // generate token and store to db
            $token = $request->user()->createToken("blog-app-token");
            // response to client
            return $this->successResponse("logged in!", $token);
        } else {
            return $this->failResponse("Wrong credentials!", null, 401);
        }
    } 

    /**
     * Logout
    */
    public function logout (Request $request) {
        Auth::user()->currentAccessToken()->delete();
        return $this->successResponse("logged out!", null);
        // return $request->user()->currentAccessToken()->name;
    } 
}

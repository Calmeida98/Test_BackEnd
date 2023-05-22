<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use \App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     * to configure Ttl auth()->setTtl($seconds);
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages([
                'error' => ['The credentials are incorrect']
            ]);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            throw ValidationException::withMessages([
                'login'=>true,
                'error' => ['Incorret user and password ']
            ]);
        }
        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $user= new User();
        $statuscode=201;
        $user->fill($request->all());
        $valid = $user->self_validate('create');
        if (!$valid['success']) {
            $result = ["success" => false, "errors" => $valid];
            $statuscode=406;
        } else {
            $result=[
                'message' => 'User successfully registered',
                'user' => $user
            ];
            $user->save();
        }

        return response()->json($result, $statuscode);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        DB::beginTransaction();
        try {
            $refresh_token = $request->header('refresh_token');
            auth()->logout();
            if ($refresh_token)
                auth()->invalidate_token($refresh_token);
                DB::commit();
            return response()->json(['message' => 'User successfully signed out']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'refresh_token' => auth()->refresh_token()
        ]);
    }

    /**
     * Create a refresh token for a user.
     *
     * @param  \Tymon\JWTAuth\Contracts\JWTSubject  $user
     *
     * @return string
     */
    public function refresh_token($second=60)
    {
        $ttl=$this->jwt->factory()->getTTL();
        $this->setTTL($ttl+$second);
        $token= $this->jwt->fromUser($this->user);
        $this->setTTL($ttl);
        return $token;
    }
}


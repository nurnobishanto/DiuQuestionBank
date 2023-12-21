<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_id' => 'required|string|max:15|unique:users,user_id',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'status' => false,'errors' => $validator->errors()], 422);
        }

        // Continue with registration logic if validation passes

        // For example, you might create a new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_id' => $request->input('user_id'),
            'password' => Hash::make($request->input('password')),
        ]);
        $credentials = $request->only('user_id', 'password');
        $token = $this->guard()->attempt($credentials);
        return $this->respondWithToken($token);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('user_id', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['status' => 0,'error' => 'Unauthorized']);
    }

    public function me()
    {
        $userId = Auth::guard()->user()->id;
        $activeSubscription = Subscription::where('user_id', $userId)
            ->where('end_date', '>', now())
            ->orderBy('end_date', 'desc')
            ->first();

        if ($activeSubscription) {
            $remainingDays = Carbon::parse($activeSubscription->end_date)->diffInDays(now());
            return response()->json([
                'status' => 'active',
                'remaining_days' => $remainingDays,
                'end_date' => $activeSubscription->end_date,
                'user' => $this->guard()->user(),
            ]);
        } else {
            return response()->json([
                'status' => 'inactive',
                'user' => $this->guard()->user(),
            ]);
        }


    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 1,
            'user' => $this->guard()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}

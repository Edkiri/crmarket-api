<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\Users\UserLoginRequest;
use App\Http\Requests\Users\UserSignupRequest;
use App\Models\Market;
use App\Models\Role;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function signup(UserSignupRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Market::create([
            'name' => $validated['projectName'],
            'slug_name' => Str::slug($validated['projectName']),
            'user_id' => $user->id,
        ]);

        return response()->json([
            'data' => $user,
            'message' => 'User created successfully.',
        ], Response::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email or password incorrect'
            ], Response::HTTP_FORBIDDEN);
        }

        $validPassword = Hash::check($validated['password'], $user->password);

        if (!$validPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Email or password incorrect'
            ], Response::HTTP_FORBIDDEN);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $market = Market::where('slug_name', $validated['slug_name'])->firstOrFail();
        $role_id = $user->markets()
            ->where('markets.id', $market->id)
            ->withPivot('role_id')
            ->first()
            ->pivot->role_id;

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => $user,
                'market' => $market,
                'role_id' => $role_id
            ]
        ], Response::HTTP_OK);
    }
}

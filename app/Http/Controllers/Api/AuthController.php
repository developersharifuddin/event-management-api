<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $userData['password'] = bcrypt($userData['password']);
        $userData['email_verified_at'] = now();
        $user = User::create($userData);

        try {

            $response = Http::post(env('OAUTH_TOKEN_URL'), [
                'grant_type' => 'password',
                'client_id' =>  env('PASSPORT_PASSWORD_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
                'username' => $userData['email'],
                'password' => $request->password,
                'scope' => '',
            ]);

            if ($response->failed()) {
                Log::error('OAuth token request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json([
                    'success' => false,
                    'statusCode' => 500,
                    'message' => 'Failed to get access token.',
                ], 500);
            }

            $user['token'] = $response->json();

            return response()->json([
                'success' => true,
                'statusCode' => 201,
                'message' => 'User has been registered successfully.',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('An error occurred during user registration', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => $e->getmessage(),
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $response = Http::post(env('OAUTH_TOKEN_URL'), [
                    'grant_type' => 'password',
                    'client_id' =>  env('PASSPORT_PASSWORD_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
                    'username' => $request->email,
                    'password' => $request->password,
                ]);

                if ($response->successful()) {
                    $user['token'] = $response->json();
                    // $user->createToken('token')->accessToken;
                    return response()->json([
                        'success' => true,
                        'statusCode' => 200,
                        'message' => 'User has been logged successfully.',
                        'data' => $user,
                    ], 200);
                } else {
                    Log::error('OAuth token request failed', ['response' => $response->body()]);
                    return response()->json([
                        'success' => false,
                        'statusCode' => 500,
                        'message' => 'OAuth token request failed.',
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'statusCode' => 401,
                    'message' => 'Unauthorized.',
                    'errors' => 'Unauthorized',
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error('An error occurred during user login', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /** Login user  */
    public function me(): JsonResponse
    {
        try {
            $user = auth()->user();

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Authenticated use info.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            Log::error('An error occurred during user login', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'User logged out successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => 'An error occurred while logging out.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

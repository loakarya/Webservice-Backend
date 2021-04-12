<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\Employee;
use Illuminate\Http\Request;
use Google_Client;

class AuthController extends Controller
{
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'loginGoogle']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login( Request $request )
    {
        $credentials = request(['email', 'password']);

        if ($request->filled('remember')) {
            if (! $token = auth()->setTTL(2592000)->attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } else {
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }
        
        $user = auth()->user()->first();
        $user->last_ip = $request->ip();
        $user->save();

        return $this->respondWithToken($token);
    }

    public function loginGoogle()
    {
        $id_token = request( 'token' );
        $client = new Google_Client(['client_id' => config('services.GOOGLEAPI_CLIENT_ID')]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $employee = Employee::where('private_email', $payload['email']);
            if ( $employee->doesntExist() ) return response()->json(['message', 'There is no employee account exists for email ' . $payload->email], 401);
            $employee = $employee->first();
            return $this->respondWithToken( auth()->tokenById($employee->user()->first()->id) ) ;
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out.']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

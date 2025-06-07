<?php

namespace App\Http\Controllers;

use App\Services\CognitoService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $cognito;

    public function __construct(CognitoService $cognito)
    {
        $this->cognito = $cognito;
    }

    /**
     * Handle the login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $token = $this->cognito->authenticate($request->username, $request->password);
            dd($token);
            if (!$token) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed'], 500);
        }

        return response()->json(['token' => $token]);
    }
}

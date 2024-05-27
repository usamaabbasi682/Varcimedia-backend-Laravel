<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): LoginResource
    {
        try {
            $request->authenticate();
            
            $user = $request->user();
            $token = $user->createToken('SPA_TOKEN')->plainTextToken;

            return LoginResource::make($user)
                ->additional([
                    'success' => true,
                    'message' => 'User login successfully',
                    'token' => $token,
                ]);

        } catch (ValidationException $e) {
            
            return LoginResource::make(null)
                ->additional([
                    'success' => false,
                    'message' => 'Email or Password is incorrect',
                ]);
                
        } catch (\Exception $e) {

            return LoginResource::make(null)
                ->additional([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.',
                ]);
        }  catch (\Error $e) {

            return LoginResource::make(null)
                ->additional([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.',
                ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): LoginResource
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return LoginResource::make(null)
            ->additional([
                'success' => true,
                'message' => 'Logout successfully',
            ]);
    }
}

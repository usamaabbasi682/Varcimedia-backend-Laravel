<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['verify' => true,'message' => 'your email address has been successfully verified']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['verify' => false ,'message' => 'verification-link-sent']);
    }
}

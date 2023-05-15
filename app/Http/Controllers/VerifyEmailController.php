<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verifyEmail ($id) {
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return request()->wantsJson()
               ? new JsonResponse('',204)
               : redirect(url(env('FRONT_URL')).'/verify/success');
        }
        return request()->wantsJson()
               ? new JsonResponse('',204)
               : redirect(url(env('FRONT_URL')).'/verify/success');
    }

    public function resend () {
        request()->user()->sendEmailVerificationNotification();
        return response([
            'data' => [
                'message' => 'request has beend sent successfully'
            ]
        ]);
    }
}

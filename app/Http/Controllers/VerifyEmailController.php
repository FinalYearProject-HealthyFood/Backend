<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verifyEmail ($id, $token) {
        $user = User::findOrFail($id);
        if (! hash_equals($token, sha1($user->email))) {
            abort(403, 'Invalid verification URL');
        }
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            // event(new Verified($user));
            return request()->wantsJson()
               ? new JsonResponse('',204)
               : redirect(url(env('APP_FRONT_URL')).'/verify/success');
        }
        return request()->wantsJson()
               ? new JsonResponse('',204)
               : redirect(url(env('APP_FRONT_URL')).'/verify/already-verify');
    }

    public function resend () {
        request()->user()->sendVerificationEmail();
        return response([
            'data' => [
                'message' => 'request has beend sent successfully'
            ]
        ]);
    }
}

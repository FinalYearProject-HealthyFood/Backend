<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                "errors" =>
                [
                    'error' => ['Không tìm thấy tài khoản']
                ]
            ], 422);
        }
        $user->sendResetPasswordEmail();
        return response()->json(['message' => 'Reset password email sent']);
    }

    public function resetPasswordWithPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'pin' => 'required|digits:6',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải là email hợp lệ.',
            'pin.required' => 'Pin là bắt buộc.',
            'pin.digits' => 'Pin là 6 chữ số.',
            'password.required' => 'password là bắt buộc.',
            'password.string' => 'password phải là chuỗi.',
            'password.min' => 'password phải lớn hơn 6 ký tự.',
            'password_confirmation.required' => 'password confirmation là bắt buộc.',
            'password_confirmation.same' => 'password confirmation phải giống với password.',
        ]);

        $email = $request->input('email');
        $pin = $request->input('pin');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                "errors" =>
                [
                    'error' => ['Không tìm thấy tài khoản']
                ]
            ], 422);
        }
        if (!$user->checkPinValid($pin)) {
            return response()->json([
                "errors" =>
                [
                    'error' => ['Mã PIN không hợp lệ hoặc liên kết đã hết hạn']
                ]
            ], 422);
        } else {
            $user->resetPasswordUsingPin($pin, $password);
        }
        return response()->json(['message' => 'Password reset successful']);
    }
}

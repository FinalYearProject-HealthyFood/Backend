<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     */

    public function index(Request $request)
    {
        $users = User::where('name', 'like', '%' . $request->search . '%')->paginate(5);
        return response()->json($users);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $edit_user = User::find($user->id);
        $edit_user->name = $request->name;
        $edit_user->email = $request->email;
        $edit_user->age = $request->age;
        $edit_user->weight = $request->weight;
        $edit_user->height = $request->height;
        $edit_user->gender = $request->gender;
        $edit_user->address = ($request->address !== null) ? $request->address : "";
        $edit_user->phone = ($request->phone !== null) ? $request->phone : "";
        $edit_user->save();
        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $edit_user,
        ]);
    }

    public function updateByManager(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
            ],
            [
                'name.required' => 'Tên là bắt buộc',
                'name.max' => 'Tên phải có độ dài tối đa là 255 ký tự',
                'name.string' => 'Tên phải là 1 chuỗi ký tự',
                'email.required' => 'Trường Email là bắt buộc',
                'email.email' => 'Email phải là email hợp lệ',
                'email.string' => 'Email phải là 1 chuỗi ký tự',
                'email.unique' => 'Email này đã được sử dụng',
                'email.max' => 'Email phải có độ dài tối đa là 255 ký tự',
                // 'password' => 'required|string|min:6',
            ]
        );
        $edit_user = User::find($request->id);
        $edit_user->name = $request->name;
        $edit_user->email = $request->email;
        $edit_user->age = $request->age;
        $edit_user->weight = $request->weight;
        $edit_user->height = $request->height;
        $edit_user->gender = $request->gender;
        $edit_user->address = ($request->address !== null) ? $request->address : "";
        $edit_user->phone = ($request->phone !== null) ? $request->phone : "";
        ($request->verify == "yes") ? $edit_user->email_verified_at = now() : $edit_user->email_verified_at = null;
        $edit_user->save();
        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $edit_user,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->age = $request->age;
        $user->weight = $request->weight;
        $user->height = $request->height;
        ($request->gender !== null) ? $user->gender = $request->gender : "";
        $user->address = ($request->address !== null) ? $request->address : "";
        $user->phone = ($request->phone !== null) ? $request->phone : "";
        $user->password = Hash::make($request->password);
        ($request->verify == "yes") ? $user->email_verified_at = now() : "";
        $user->save();
        return response()->json([
            'message' => 'Account created successfully',
            'data' => $user,
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'message' => 'Xóa tài khoản thành công',
            'data' => $user,
        ]);
    }

    public function changePassword(Request $request)
    {
        $user_id = Auth::user()->id;
        $input = array(
            // 'old_password' => $request->old_password,
            'new_password' => $request->new_password,
            'confirm_password' => $request->confirm_password,
        );
        $rule = array(
            // 'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rule);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404);
        } else {
            $user = User::findOrFail($user_id);
            if ((Hash::check($request->new_password, $user->password)) == true) {
                return response()->json(['message' => " please enter a new password"]);
            } else {
                $user->update(['password' => Hash::make($request->new_password)]);
                return response()->json($user, 200);
            }
            return response()->json(['message' => "User not found"]);
        }
    }
}

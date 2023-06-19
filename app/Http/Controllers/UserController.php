<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
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
        $users = User::with('role')
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhereHas('role', function ($roleQuery) use ($request) {
                        $roleQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            })
            ->paginate(5);

        return response()->json($users);
    }

    public function getTodayCaloriesEatenByUser($id)
    {
        $sumCalories = OrderItem::where('user_id', $id)
            ->where('for_me', 'yes')
            ->where('order_items.status', 'delivered')
            ->whereDate('order_items.updated_at', '>=', now()->startOfDay())
            ->join('meals', 'order_items.meal_id', '=', 'meals.id')
            ->sum('meals.calories');
        $user = User::find($id);
        $caloriesPerday = 1500;
        if (
            $user->weight !== null && $user->weight !== "" &&
            $user->height !== null && $user->height !== "" &&
            $user->age !== null && $user->age !== "" &&
            $user->activity !== null && $user->activity !== "" &&
            $user->gender !== null && $user->gender !== ""
        ) {
            $caloriesPerday = round($this->mifflin_cal($user->weight, $user->height, $user->age, $user->activity, $user->gender));
        } else {
            $caloriesPerday = 1500;
        }
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $orderItems = OrderItem::whereBetween('created_at', [$startDate, $endDate])
            ->with('meal')->with('ingredient')
            ->where("status", "delivered")
            ->where("for_me", "yes")
            ->where("user_id", $user->id)
            ->orderBy('id', 'DESC')
            ->get();

        $count = $orderItems->count();
        if ($user->plan > $count) {
            return response()->json([
                "eatencalories" => $sumCalories,
                "caloriesperday" => $caloriesPerday,
                "calorieswilleat" => round(($caloriesPerday - $sumCalories) / ($user->plan - $count)),
                "plan" => $user->plan,
                "countdiet" => $count,
            ]);
        }
        return response()->json([
            "eatencalories" => $sumCalories,
            "caloriesperday" => $caloriesPerday,
            "calorieswilleat" => round(($caloriesPerday) / ($user->plan)),
            "plan" => $user->plan,
            "countdiet" => $count,
        ]);
    }
    function mifflin_cal($w, $h, $a, $activity, $g)
    {
        if ($g == "male") {
            return floor((10 * $w + 6.25 * $h - 5 * $a + 5) * $activity);
        } else {
            return floor((10 * $w + 6.25 * $h - 5 * $a - 161) * $activity);
        }
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
        $edit_user->activity = $request->activity;
        $edit_user->plan = $request->plan;
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
        $role = Role::findOrFail($request->role);
        $edit_user->role()->associate($role);
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
        $role = Role::findOrFail($request->role);
        $user->role()->associate($role);
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

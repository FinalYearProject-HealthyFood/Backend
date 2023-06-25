<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\ResetPasswordEmail;
use App\Mail\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }
    public function orderItems()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function ingredient_rating()
    {
        return $this->hasMany(RatingIngredient::class, 'user_id', 'id');
    }

    public function meal_rating()
    {
        return $this->hasMany(Rating::class, 'user_id', 'id');
    }

    public function meals()
    {
        return $this->hasMany(Meal::class,'user_id','id')->with('ingredients');
    }

    public function hasRole($roleName)
    {
        return $this->role->name === $roleName;
    }

    public function sendVerificationEmail()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60), // Expiration time for the URL (adjust as needed)
            ['id' => $this->id, 'token' => sha1($this->email)]
        );
        $data = array(
            'name' => $this->name,
            'verificationUrl' => $verificationUrl,
        );
        Mail::to($this->email)->send(new VerifyEmail($data));
    }

    public function sendResetPasswordEmail()
    {
        $pin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT); // Generate a random 6-digit PIN code
        $expirationTime = now()->addMinutes(60); // Set the expiration time to 60 minutes from now

        $passwordReset = DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->email],
            ['token' => $pin, 'expires_at' => $expirationTime, 'created_at' => now()]
        );
        // Send the email with the PIN code
        Mail::to($this->email)->send(new ResetPasswordEmail([
            "email" => $this->email,
            "pin" => $pin,
        ]));
    }

    public function checkPinValid($pin)
    {
        $token = DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->where('token', $pin)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        if ($token) {
            return true;
        } else {
            return false;
        }
    }

    public function resetPasswordUsingPin($pin, $password)
    {
        $this->update(['password' => Hash::make($password)]);
        // Delete the used password reset token from the table
        DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->where('token', $pin)
            ->delete();
    }
}

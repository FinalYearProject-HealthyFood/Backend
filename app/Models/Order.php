<?php

namespace App\Models;

use App\Mail\OrderEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'order_id','id');
    }

    public function sendOrderDetailEmail()
    {
        $data = array(
            'name' => $this->user->name,
            'orderItem' => $this->orderItems,
            'order' => $this,
        );
        Mail::to($this->user->email)->send(new OrderEmail($data));

        // if(count(Mail::failures())>0) {
        //     return response()->json(["data" => "fail"], 200);
        // }
        // try {
        //     Mail::to($this->user->email)->send(new OrderEmail($data));
        //     $isSent =  true;
        // } catch (\Exception $e) {
        //     $isSent = false;
        // }

        // if($isSent) {
        //     return response()->json(["data" => "success"], 200);
        // }
        // else {
        //     return response()->json(["data" => "fail"], 200);
        // }
    }
}

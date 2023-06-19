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
        return $this->hasMany(OrderItem::class,'order_id','id')->with("ingredient")->with("meal");
    }

    public function sendOrderDetailEmail()
    {
        $data = array(
            'name' => $this->user->name,
            'orderItem' => $this->orderItems,
            'order' => $this,
            'status' => $this->status,
        );
        Mail::to($this->user->email)->send(new OrderEmail($data));
    }
}

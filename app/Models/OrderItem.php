<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class,'meal_id','id')->with("ingredients");
    }
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class,'ingredient_id','id');
    }
}

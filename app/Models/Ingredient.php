<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_ingredients')->withPivot('quantity');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'ingredient_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'meal_ingredients')->withPivot('quantity');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'meal_id', 'id');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'meal_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function is_rating_by_auth($id)
    {
        $raters = array();

        foreach ($this->rating as $rate) :
            array_push($raters, $rate->user_id);
        endforeach;

        if (in_array($id, $raters)) {
            return true;
        } else {
            return false;
        }
    }
}

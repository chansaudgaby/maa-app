<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   
    protected $fillable = [
        'menu_id', 'date', 'user_id', 'quantity', 'meal_id'
    ];

}

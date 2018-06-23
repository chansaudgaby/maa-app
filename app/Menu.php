<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
   
    protected $fillable = [
        'meal_id', 'date', 'user_id', 'quantity', 'orders'
    ];

}

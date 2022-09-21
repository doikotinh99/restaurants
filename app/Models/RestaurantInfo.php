<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "name",
        "address",
        "time_start",
        "time_end",
        "tables",
    ];
}

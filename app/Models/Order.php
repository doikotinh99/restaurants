<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "restaurant_id",
        "table_id",
        "arrival_time",
        "status"
    ];

    public function user(){
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function restaurant(){
        return $this->hasOne(Restaurant::class, "id", "restaurant_id");
    }

    public function table(){
        return $this->hasOne(TableInfo::class, "id", "table_id");
    }

    public function orderDetail(){
        return $this->hasMany(OrderDetail::class);
    }
}

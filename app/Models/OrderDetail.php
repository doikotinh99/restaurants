<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "order_id",
        "eating_id",
        "quanlity"
    ];

    public function eating(){
        return $this->hasOne(Eating::class, "id", "eating_id");
    }

    public function order(){
        return $this->hasOne(Order::class, "id", "order_id");
    }
}

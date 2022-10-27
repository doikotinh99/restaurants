<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "vote",
        "discription",
        "restaurant_id"
    ];

    public function isUser(){
        return $this->hasOne(User::class, "id", "user_id");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eating extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "price",
        "discount",
        "status",
        "restaurant_id",
        "description"
    ];

    public function images(){
        return $this->morphMany(Image::class, "imageble");
    }

    public function restaurant(){
        return $this->hasOne(Restaurant::class, "id", "restaurant_id");
    }
}

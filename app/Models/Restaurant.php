<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "name",
        "address",
        "time_start",
        "time_end"
    ];

    public function user(){
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function tables(){
        return $this->hasMany(TableInfo::class);
    }

    public function menu(){
        return $this->hasMany(Eating::class);
    }

    public function addres(){
        return $this->hasOne(Address::class);
    }

    public function images(){
        return $this->morphMany(Image::class, "imageble");
    }
}

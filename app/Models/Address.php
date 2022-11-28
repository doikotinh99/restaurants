<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        "city",
        "district",
        "wards",
        "detail"
    ];

    public function isCity(){
        return $this->hasOne(Address_city::class, "id", "city");
    }
    
    public function isDistrict(){
        return $this->hasOne(Address_district::class, "id", "district");
    }

    public function isWards(){
        return $this->hasOne(Ward::class, "id", "wards");
    }
}

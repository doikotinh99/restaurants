<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        "type",
        "chair",
        "count",
        "status",
        "restaurant_id",
        "description"
    ];

    public function images(){
        return $this->morphMany(Image::class, "imageble");
    }
}

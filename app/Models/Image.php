<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        "path",
        "imageble_id",
        "imageble_type"
    ];

    public function imageble(){
        return $this->morphTo();
    }
}

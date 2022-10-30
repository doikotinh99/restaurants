<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "content",
        "title",
        "description",
        "status"
    ];

    public function images(){
        return $this->morphMany(Image::class, "imageble");
    }
    public function comments(){
        return $this->morphMany(Comment::class, "commentable");
    }
    public function user(){
        return $this->hasOne(User::class, "id", "user_id");
    }
}

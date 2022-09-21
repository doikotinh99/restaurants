<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "parent_id",
        "content",
    ];

    public function commentable(){
        return $this->morphTo();
    }

    public function repComment(){
        return $this->morphMany(Comment::class, "commentable");
    }

    public function childComment(){
        return $this->hasMany(Comment::class, "parent_id", "id");
    }
}

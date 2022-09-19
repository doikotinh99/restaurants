<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfor extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "phone",
        "address",
        "gender",
        "birday"
    ];

    public function user(){
        return $this->hasOne(User::class);
    }
}

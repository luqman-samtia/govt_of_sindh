<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SigningAuthority;
use App\Models\Designation;
use App\Models\ForwardCopy;
use App\Models\ToLetter;
use App\Models\User;

class Letter extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function designations()
    {
        return $this->hasMany(ToLetter::class);
    }

    public function signingAuthorities()
    {
        return $this->hasMany(SigningAuthority::class);
    }

    public function forwardedCopies()
    {
        return $this->hasMany(ForwardCopy::class);
    }
    // public function ToLetters()
    // {
    //     return $this->hasMany(ToLetter::class);
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

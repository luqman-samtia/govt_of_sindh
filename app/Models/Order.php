<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderSigningAuthority;
use App\Models\Designation;
use App\Models\OrderForwardCopy;
use App\Models\OrderToLetter;
use App\Models\User;


class Order extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function designations()
    {
        return $this->hasMany(OrderToLetter::class);
    }

    public function signingAuthorities()
    {
        return $this->hasMany(OrderSigningAuthority::class);
    }

    public function forwardedCopies()
    {
        return $this->hasMany(OrderForwardCopy::class);
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

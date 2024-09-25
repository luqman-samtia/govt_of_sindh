<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Letter;

class ForwardCopy extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }
}

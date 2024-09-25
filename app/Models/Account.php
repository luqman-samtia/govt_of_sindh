<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $fillable = ['holder_name', 'bank_name', 'account_number', 'balance', 'address'];

    /**
     * @var string[]
     */
    protected $casts = [
        'holder_name' => 'string',
        'bank_name' => 'string',
        'account_number' => 'string',
        'balance' => 'double',
        'address' => 'string',
    ];

    public static $rules = [
        'holder_name' => 'required',
        'bank_name' => 'required',
        'account_number' => 'required',
        'balance' => 'required|numeric',
    ];
}

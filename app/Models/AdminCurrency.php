<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminCurrency
 *
 * @property int $id
 * @property string $name
 * @property string $icon
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|AdminCurrency newModelQuery()
 * @method static Builder|AdminCurrency newQuery()
 * @method static Builder|AdminCurrency query()
 * @method static Builder|AdminCurrency whereCode($value)
 * @method static Builder|AdminCurrency whereCreatedAt($value)
 * @method static Builder|AdminCurrency whereIcon($value)
 * @method static Builder|AdminCurrency whereId($value)
 * @method static Builder|AdminCurrency whereName($value)
 * @method static Builder|AdminCurrency whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AdminCurrency extends Model
{
    use HasFactory;

    protected $table = 'admin_currencies';

    protected $fillable = ['name', 'icon', 'code'];

    protected $casts = [
        'name' => 'string',
        'icon' => 'string',
        'code' => 'string',
    ];

    public static $rules = [
        'name' => 'required|string|unique:admin_currencies,name',
        'icon' => 'required|unique:admin_currencies,icon',
        'code' => 'required|min:3|max:3',
    ];
}

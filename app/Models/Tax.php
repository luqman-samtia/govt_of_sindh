<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

/**
 * App\Models\Tax
 *
 * @property int $id
 * @property string $name
 * @property float $value
 * @property string|null $tenant_id
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MultiTenant|null $tenant
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Tax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tax whereValue($value)
 *
 * @mixin \Eloquent
 */
class Tax extends Model
{
    use HasFactory, BelongsToTenant, Multitenantable;

    protected $table = 'taxes';

    protected $fillable = ['name', 'value', 'is_default', 'tenant_id'];

    /**
     * @var string[]
     */
    protected $casts = [
        'name' => 'string',
        'value' => 'double',
        'is_default' => 'integer',
    ];

    public static $rules = [
        'name' => 'required|is_unique:taxes,name|max:191',
        'value' => 'required|numeric',
    ];
}

<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

/**
 * App\Models\InvoiceSetting
 *
 * @property int $id
 * @property string $key
 * @property string $template_name
 * @property string $template_color
 * @property string|null $tenant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MultiTenant|null $tenant
 *
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting whereTemplateColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting whereTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceSetting whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class InvoiceSetting extends Model
{
    use HasFactory, BelongsToTenant, Multitenantable;

    protected $table = 'invoice-settings';

    protected $fillable = ['key', 'template_name', 'template_color', 'tenant_id'];

    protected $casts = [
        'key' => 'string',
        'template_name' => 'string',
        'template_color' => 'string',
    ];
}

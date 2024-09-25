<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\TenantWiseClient
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $client_id
 * @property string|null $tenant_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|TenantWiseClient newModelQuery()
 * @method static Builder|TenantWiseClient newQuery()
 * @method static Builder|TenantWiseClient query()
 * @method static Builder|TenantWiseClient whereClientId($value)
 * @method static Builder|TenantWiseClient whereCreatedAt($value)
 * @method static Builder|TenantWiseClient whereId($value)
 * @method static Builder|TenantWiseClient whereTenantId($value)
 * @method static Builder|TenantWiseClient whereUpdatedAt($value)
 * @method static Builder|TenantWiseClient whereUserId($value)
 *
 * @mixin Eloquent
 */
class TenantWiseClient extends Model
{
    /**
     * @var string
     */
    protected $table = 'tenant_wise_clients';

    /**
     * @var string[]
     */
    protected $fillable = ['user_id', 'client_id', 'tenant_id'];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'client_id' => 'integer',
        'tenant_id' => 'string',
    ];
}

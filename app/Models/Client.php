<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Stancl\Tenancy\Database\TenantScope;

/**
 * App\Models\Client
 *
 * @property int $id
 * @property int $user_id
 * @property int $country_id
 * @property int $state_id
 * @property int $city_id
 * @property string $postal_code
 * @property string|null $website
 * @property string $address
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Client newModelQuery()
 * @method static Builder|Client newQuery()
 * @method static Builder|Client query()
 * @method static Builder|Client whereAddress($value)
 * @method static Builder|Client whereCityId($value)
 * @method static Builder|Client whereCountryId($value)
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereNote($value)
 * @method static Builder|Client wherePostalCode($value)
 * @method static Builder|Client whereStateId($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static Builder|Client whereUserId($value)
 * @method static Builder|Client whereWebsite($value)
 * @property string|null $tenant_id
 * @property-read City|null $city
 * @property-read Country|null $country
 * @property-read Collection|Invoice[] $invoices
 * @property-read int|null $invoices_count
 * @property-read State|null $state
 * @property-read MultiTenant|null $tenant
 * @property-read User $user
 * @method static Builder|Client whereTenantId($value)
 * @property string|null $vat_no
 * @property bool|null $is_password_set
 * @method static Builder|Client whereIsPasswordSet($value)
 * @method static Builder|Client whereVatNo($value)
 * @property string|null $company_name
 * @method static Builder|Client whereCompanyName($value)
 * @mixin Eloquent
 */
class Client extends Model
{
    use HasFactory, BelongsToTenant, Multitenantable;

    protected $table = 'clients';

    public $fillable = [
        'website',
        'postal_code',
        'address',
        'note',
        'country_id',
        'state_id',
        'city_id',
        'tenant_id',
        'user_id',
        'is_password_set',
        'vat_no',
        'company_name',
    ];

    protected $casts = [
        'website' => 'string',
        'postal_code' => 'string',
        'address' => 'string',
        'note' => 'string',
        'country_id' => 'integer',
        'state_id' => 'integer',
        'city_id' => 'integer',
        'tenant_id' => 'string',
        'user_id' => 'integer',
        'is_password_set' => 'boolean',
        'vat_no' => 'string',
        'company_name' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'first_name' => 'required|max:191',
        'last_name' => 'required|max:191',
        'email' => 'nullable|email:filter|regex:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/',
        'password' => 'nullable|same:password_confirmation|min:6',
        'contact' => 'nullable|is_unique:users,contact',
        'postal_code' => 'string',
        'address' => 'nullable|string',
        'website' => 'nullable|url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withoutGlobalScope(new TenantScope());
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id', 'id')->withoutGlobalScope(new TenantScope());
    }
}

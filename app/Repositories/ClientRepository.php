<?php

namespace App\Repositories;

use App\Mail\CreateNewClientMail;
use App\Mail\ExistClientUseMail;
use App\Models\Client;
use App\Models\MultiTenant;
use App\Models\SuperAdminSetting;
use App\Models\TenantWiseClient;
use App\Models\User;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Stancl\Tenancy\Database\TenantScope;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class ClientRepository
 *
 * @version August 6, 2021, 10:17 am UTC
 */
class ClientRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'website',
        'address',
    ];

    /**
     * Return searchable fields
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return Client::class;
    }

    public function store($input, $existUser): bool
    {
        try {
            DB::beginTransaction();

            if (empty($existUser)) {
                $tenant = MultiTenant::create(['tenant_username' => $input['first_name']]);
            }

            $input['client_password'] = $input['password'];
            $input['password'] = Hash::make($input['password']);
            $input['role'] = getClientRoleId();

            if (empty($existUser)) {
                $user = User::create($input);
                $user->assignRole($input['role']);
                $input['user_id'] = $user->id;
                $client = Client::create($input);
            } else {
                $user = $existUser;
                $user->assignRole($input['role']);
                $input['user_id'] = $user->id;
                $client = getClient($user->id);
            }

            TenantWiseClient::create([
                'user_id' => $user->id,
                'client_id' => $client->id,
                'tenant_id' => getLogInUser()->tenant_id,
            ]);

            if (isset($input['profile']) && ! empty($input['profile'])) {
                $user->addMedia($input['profile'])->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }

            if (! empty($input['email']) && getSettingValue('mail_notification')) {
                if (! empty($existUser)) {
                    Mail::to($existUser->email)->send(new ExistClientUseMail($existUser));
                } else {
                    Mail::to($input['email'])->send(new CreateNewClientMail($input));
                }
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateClient(array $input, Client $client): bool
    {
        try {
            DB::beginTransaction();
            $user = User::withoutGlobalScope(new TenantScope())->whereId($client->user_id)->first();
            $userInputs = Arr::only($input, ['first_name', 'last_name', 'email', 'contact', 'region_code']);

            if (! empty($input['password'])) {
                $userInputs['password'] = Hash::make($input['password']);
            }

            $user->update($userInputs);
            $clientInputs = Arr::only($input,
                ['website', 'postal_code', 'country_id', 'state_id', 'city_id', 'address', 'note','vat_no','company_name']);
            $client->update($clientInputs);

            if (isset($input['profile']) && ! empty($input['profile'])) {
                $user->clearMediaCollection(User::PROFILE);
                $user->media()->delete();
                $user->addMedia($input['profile'])->toMediaCollection(User::PROFILE,
                    config('app.media_disc'));
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}

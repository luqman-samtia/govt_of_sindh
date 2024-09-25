<?php

namespace App\Repositories;

use App\Models\MultiTenant;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class SuperAdminRepository
 */
class SuperAdminRepository extends BaseRepository
{
    public $fieldSearchable = [
        'first_name',
        'last_name',
        'email',
    ];

    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return User::class;
    }

    public function store($input): bool
    {
        try {
            DB::beginTransaction();
            $tenant = MultiTenant::create(['tenant_username' => $input['first_name']]);
            $input['password'] = Hash::make($input['password']);
            $input['tenant_id'] = $tenant->id;
            $input['email_verified_at'] = Carbon::now();

            $user = User::create($input);
            $user->designation = $input['designation'];
            $user->district = $input['district'];
            $user->grade = $input['grade'];
            $user->zone = $input['zone'];

            $user->assignRole(getSuperAdminRoleId());
            $user->save();
            if (isset($input['profile']) && ! empty($input['profile'])) {
                $user->addMedia($input['profile'])->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateSuperAdmin(array $input, int $id): bool
    {
        try {
            DB::beginTransaction();

            if (! empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            }

            $user = User::find($id);
            $user->update($input);
            $user->designation = $input['designation'];
            $user->district = $input['district'];
            $user->grade = $input['grade'];
            $user->zone = $input['zone'];
            $user->save();
            if (isset($input['profile']) && ! empty($input['profile'])) {
                $user->clearMediaCollection(User::PROFILE);
                $user->media()->delete();
                $user->addMedia($input['profile'])->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}

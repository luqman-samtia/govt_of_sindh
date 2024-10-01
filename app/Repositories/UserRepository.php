<?php

namespace App\Repositories;

use App\Models\MultiTenant;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class UserRepository
 */
class UserRepository extends BaseRepository
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


            $user = $this->createUser($input, $tenant);
            $user->email_verified_at = now();
            $user->designation = strtolower($input['designation']);
            $user->district = $input['district'];
            $user->grade = $input['grade'];
            $user->zone = $input['zone'];
            
            $words = explode(' ', $input['district']);
            $prefix = '';
            foreach ($words as $word) {
                $prefix .= strtoupper(substr($word, 0, 1));
                if (strlen($prefix) == 2) break;
            }
            $prefix = substr($prefix . 'XX', 0, 2); // Ensure we always have 2 characters
            
            $year = date('Y');
            
            $letter_no = "{$prefix}-SND-{$year}-";
            
            $user->letter_no = $letter_no;
            $user->sendEmailVerificationNotification();
            $user->save();
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateUser(array $input, int $id): bool
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);

            // if (!empty($input['password'])) {
            //     $input['password'] = Hash::make($input['password']);
            // }
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                // Remove password field if it's not being updated to avoid nulling it
                unset($input['password']);
            }

            $user->update($input);
            $user->designation = strtolower($input['designation']);
            $user->district = $input['district'];
            $user->grade = $input['grade'];
            $user->letter_no = $input['letter_no'];
            $user->zone = $input['zone'];
           

            if (isset($input['profile']) && ! empty($input['profile'])) {
                $user->clearMediaCollection(User::PROFILE);
                $user->media()->delete();
                $user->addMedia($input['profile'])->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }
            $user->save();
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateProfile(array $userInput): bool
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $user->update($userInput);
            if ($user->hasRole(Role::ROLE_ADMIN)) {
                // Only admins can update these fields
                $user->address = $userInput['address'];
                $user->date = $userInput['date'];
                $user->tel = $userInput['tel'];
            }elseif($user->hasRole(Role::ROLE_ADMIN)){
                $user->tel = $userInput['tel'];
            }

            if ((! empty($userInput['image']))) {
                $user->clearMediaCollection(User::PROFILE);
                $user->media()->delete();
                $user->addMedia($userInput['image'])->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }
            $user->save();
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function createUser($input, $tenant): mixed
    {
        $input['password'] = Hash::make($input['password']);
        $input['tenant_id'] = $tenant->id;
        $user = User::create($input);
        $user->assignRole(getAdminRoleId());

        if (isset($input['profile']) && ! empty($input['profile'])) {
            $user->addMedia($input['profile'])->toMediaCollection(User::PROFILE, config('app.media_disc'));
        }

        // assign the default plan to the user when they registers.
        // $subscriptionPlan = SubscriptionPlan::where('is_default', 1)->first();
        // $trialDays = $subscriptionPlan->trial_days;

        // $subscription = [
        //     'user_id' => $user->id,
        //     'subscription_plan_id' => $subscriptionPlan->id,
        //     'plan_amount' => $subscriptionPlan->price,
        //     'plan_frequency' => $subscriptionPlan->frequency,
        //     'start_date' => Carbon::now(),
        //     'end_date' => Carbon::now()->addDays($trialDays),
        //     'trial_ends_at' => Carbon::now()->addDays($trialDays),
        //     'status' => Subscription::ACTIVE,
        // ];

        // Subscription::create($subscription);
        session(['tenant_id' => $tenant->id]);
        Artisan::call('db:seed', ['--class' => 'SettingsTableSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'SettingTableSeederFields', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'SettingTablePaymentGatewayFieldSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'SettingFavIconFieldSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'InvoiceSettingTableSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'InvoiceSettingTemplateSeeder', '--force' => true]);

        $this->addSettingRecord('currency_after_amount', $tenant->id, '1');
        $this->addSettingRecord('payment_auto_approved', $tenant->id, '1');
        $this->addSettingRecord('invoice_no_prefix', $tenant->id);
        $this->addSettingRecord('invoice_no_suffix', $tenant->id);
        $this->addSettingRecord('country_code', $tenant->id, '');

        return $user;
    }

    /**
     * @param  null  $value
     */
    public function addSettingRecord($key, $tenantId, $value = null): void
    {
        $settingExists = Setting::where('key', $key)
            ->where('tenant_id', $tenantId)->exists();

        if (! $settingExists) {
            Setting::create([
                'key' => $key,
                'value' => $value,
                'tenant_id' => $tenantId,
            ]);
        }
    }
}

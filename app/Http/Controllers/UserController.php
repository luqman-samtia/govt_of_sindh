<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateChangePasswordRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash;
use Stancl\Tenancy\Database\TenantScope;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Models\Letter;


class UserController extends AppBaseController
{
    /**
     * @var UserRepository
     */
    public $userRepository;

    /**
     * UserController constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): \Illuminate\View\View
    {
        $totalLetters = Letter::count();
        $users_form = Letter::withCount('user')->get();
        // $letters = Letter::where('is_submitted',0)->get();
        $letters = Letter::with('user')
            ->where('is_submitted', 0)
            ->get();
        // $draft = Letter::where('is_submitted',0)->get();
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', Role::ROLE_ADMIN);
        })->with('roles')->select('users.*');
        $data['users'] = $query->count();
        return view('users.index',compact('letters','data','users_form'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('users.create');
    }
    // : RedirectResponse
    public function store(Request $request)
    {
        $input = $request->all();
        // $validateData = [
        //     'first_name' => 'required|max:191',
        //     'last_name' => 'required|max:191',
        //     'email' => 'nullable|email|unique:users,email|regex:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/',
        //     'contact' => 'required|is_unique:users,contact',
        //     'password_confirmation' => 'required',
        //     'password' => 'required|same:password_confirmation|min:6',
        // ];
        $this->userRepository->store($input);
        // $user = User::create($input);

        Flash::success(__('messages.flash.user_created'));

        return redirect(route('users.index'));
    }

    public function show($userId): \Illuminate\View\View
    {
        $user = User::whereId($userId)
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_ADMIN);
            })->firstOrFail();

        return view('users.show', compact('user'));
    }

    public function edit($userId): \Illuminate\View\View
    {
        $user = User::whereId($userId)
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_ADMIN);
            })->with(['roles', 'media'])
            ->first();

        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userRepository->updateUser($request->all(), $user->id);

        Flash::success(__('messages.flash.user_updated'));

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     */
    public function destroy($id): JsonResponse
    {
        $user = User::whereId($id)->first();
        if (! $user) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }
        if (! $user->hasRole(Role::ROLE_ADMIN)) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }
        $clientModels = [
            Client::class,
        ];
        $result = canDelete($clientModels, 'tenant_id', $user->tenant_id);
        if ($result) {
            return $this->sendError(__('messages.flash.user_cant_deleted'));
        }
        $user->delete();

        return $this->sendSuccess(__('messages.flash.user_deleted'));
    }

    public function editProfile(): \Illuminate\View\View
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function updateProfile(UpdateUserProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user->hasRole(Role::ROLE_ADMIN)) {
            $validated = $request->validate([
                'address' => 'required',
                'date' => 'required',
                'tel' => 'required|max:11',
            ]);

        }



        $this->userRepository->updateProfile($request->all());
        Flash::success(__('messages.flash.user_profile_updated'));

        return redirect(route('profile.setting'));
    }

    public function changePassword(UpdateChangePasswordRequest $request): JsonResponse
    {
        $input = $request->all();

        try {
            /** @var User $user */
            $user = Auth::user();
            if (! Hash::check($input['current_password'], $user->password)) {
                return $this->sendError(__('messages.flash.current_password_is_invalid'));
            }
            $input['password'] = Hash::make($input['new_password']);
            $user->update($input);

            return $this->sendSuccess(__('messages.flash.password_updated'));
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function changeUserStatus(User $user): JsonResponse
    {
        $status = ! $user->status;
        $user->update(['status' => $status]);

        return $this->sendSuccess(__('messages.flash.status_updated'));
    }

    public function getAllLanguage()
    {
        $getAllLanguage = getUserLanguages();
        $currentLanguage = getLogInUser()->language;

        return $this->sendResponse(['getAllLanguage' => $getAllLanguage, 'currentLanguage' => $currentLanguage],
            __('messages.flash.language_retrieve'));
    }

    public function updateLanguage(Request $request): JsonResponse
    {
        $language = $request->get('languageName');

        $user = getLogInUser();
        $user->update(['language' => $language]);

        return $this->sendSuccess(__('messages.flash.language_updated'));
    }

    public function updateDarkMode(): JsonResponse
    {
        $user = Auth::user();
        $darkEnabled = $user->dark_mode == true;
        $user->update([
            'dark_mode' => ! $darkEnabled,
        ]);

        return $this->sendSuccess(__('messages.flash.theme_changed'));
    }

    public function isVerified(int $id): JsonResponse
    {
        $user = User::find($id);
        $emailVerified = $user->email_verified_at == null ? Carbon::now() : null;
        $user->update(['email_verified_at' => $emailVerified]);

        return $this->sendSuccess(__('messages.flash.email_verified'));
    }

    public function activeDeactiveStatus(int $id): JsonResponse
    {
        $user = User::find($id);
        $status = ! $user->status;
        User::where('tenant_id', $user->tenant_id)->update(['status' => $status]);

        return $this->sendSuccess(__('messages.flash.status_updated'));
    }

    public function userImpersonateLogout(): RedirectResponse
    {
        Auth::user()->leaveImpersonation();

        return redirect(url('super-admin/dashboard'));
    }

    public function impersonate(User $user): RedirectResponse
    {
        getLoggedInUser()->impersonate($user);

        return redirect(route('admin.dashboard'));
    }

    public function searchUsers($searchEmail): mixed
    {
        $tenantID = getLogInUser()->tenant_id;
        $user = \App\Models\User::role('client')->whereHas('clients', function (Builder $q) use ($tenantID) {
            $q->where('tenant_id', '!=', $tenantID);
        })->where('email', 'like', '%'.$searchEmail.'%')->withoutGlobalScope(new TenantScope())->get();

        return $this->sendResponse($user, 'Email search successfully.');
    }

    public function getUser($userId)
    {
        $firstUser = \App\Models\User::whereId($userId)->withoutGlobalScope(new TenantScope())->first();
        $firstUser->client = getClient($firstUser->id);

        return $this->sendResponse($firstUser, 'User retrieved successfully.');
    }
}

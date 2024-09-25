<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSuperAdminRequest;
use App\Http\Requests\UpdateSuperAdminRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\SuperAdminRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Laracasts\Flash\Flash;

class SuperAdminController extends AppBaseController
{
    /**
     * @var SuperAdminRepository
     */
    public $superAdminRepository;

    public function __construct(SuperAdminRepository $superAdminRepository)
    {
        $this->superAdminRepository = $superAdminRepository;
    }

    public function index(): \Illuminate\View\View
    {
        return view('super_admin.index');
    }

    public function create(): \Illuminate\View\View
    {
        return view('super_admin.create');
    }

    public function store(CreateSuperAdminRequest $request): RedirectResponse
    {
        $input = $request->all();
        $this->superAdminRepository->store($input);

        Flash::success(__('messages.flash.super_admin_created'));

        return redirect(route('super-admins.index'));
    }

    public function show($superAdminId): \Illuminate\View\View
    {
        $superAdmin = User::whereId($superAdminId)
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_SUPER_ADMIN);
            })
            ->with(['roles', 'media'])
            ->firstOrFail();

        return view('super_admin.show', compact('superAdmin'));
    }

    public function edit($superAdminId): \Illuminate\View\View
    {
        $superAdmin = User::whereId($superAdminId)
        ->whereHas('roles', function ($query) {
            $query->where('name', Role::ROLE_SUPER_ADMIN);
        })->with(['roles', 'media'])
        ->firstOrFail();

        return view('super_admin.edit', compact('superAdmin'));
    }

    public function update(UpdateSuperAdminRequest $request, $superAdminId): RedirectResponse
    {
        $this->superAdminRepository->updateSuperAdmin($request->all(), $superAdminId);

        Flash::success(__('messages.flash.super_admin_updated'));

        return redirect(route('super-admins.index'));
    }

    public function destroy($superAdminId)
    {
        $superAdmin = User::whereId($superAdminId)
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_SUPER_ADMIN);
            })
            ->first();
        if (! $superAdmin) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }
        $superAdmin->delete();

        return $this->sendSuccess(__('messages.flash.super_admin_deleted'));
    }
}

<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\AppBaseController;
use App\Repositories\DashboardRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\TenantScope;

class DashboardController extends AppBaseController
{
    /* @var DashboardRepository */
    public $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepo)
    {
        $this->dashboardRepository = $dashboardRepo;
    }

    public function index(): \Illuminate\View\View
    {
        $dashboardData = $this->dashboardRepository->getClientDashboardData();

        return view('client_panel.dashboard.index')->with($dashboardData);
    }

    public function changeTenantClient(Request $request): mixed
    {
        $tenantWiseClientId = $request->get('tenantId');
        $loginUser = \App\Models\User::withoutGlobalScope(new TenantScope())->whereId(getLogInUserId())->first();
        $loginUser->update(['tenant_id' => $tenantWiseClientId]);

        return $this->sendSuccess('Admin changed successfully.');
    }
}

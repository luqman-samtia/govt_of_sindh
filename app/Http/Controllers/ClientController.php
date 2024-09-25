<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\State;
use App\Models\TenantWiseClient;
use App\Models\User;
use App\Repositories\ClientRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Stancl\Tenancy\Database\TenantScope;

class ClientController extends AppBaseController
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * @throws Exception
     */
    public function index(): Factory|View|Application
    {
        return view('clients.index');
    }

    public function create(): View|Factory|Application
    {
        $countries = Country::toBase()->pluck('name', 'id')->toArray();
        $vatNoLabel = getVatNoLabel();

        return view('clients.create', compact('countries','vatNoLabel'));
    }

    public function store(CreateClientRequest $request): RedirectResponse
    {
        $input = $request->all();
        $existUser = User::whereEmail($input['email'])->withoutGlobalScope(new TenantScope())->first();

        if (! empty($existUser)) {
            $role = $existUser->getRoleNames()->first();
            $tenantID = getLogInUser()->tenant_id;
            $clientTenantIds = $existUser->clients()->pluck('tenant_id')->toArray();

            if (in_array($tenantID, $clientTenantIds) || $role == Role::ROLE_ADMIN) {
                Flash::error('The email has already been taken.');

                return redirect()->back();
            }
        }

        $this->clientRepository->store($input, $existUser);
        Flash::success(__('messages.flash.client_created'));

        return redirect()->route('clients.index');
    }

    public function show($clientId, Request $request): View|Factory|Application
    {
        $activeTab = $request->input('active', false);
        $client = Client::with('invoices.payments')->whereId($clientId)->withoutGlobalScope(new TenantScope())->first();
        $vatNoLabel = getVatNoLabel();

        return view('clients.show', compact('client', 'activeTab','vatNoLabel'));
    }

    public function edit($clientId): View|Factory|Application
    {
        $client = Client::withoutGlobalScope(new TenantScope())->whereId($clientId)->first();
        $countries = Country::toBase()->pluck('name', 'id')->toArray();
        $clientState = State::toBase()->whereCountryId($client->country_id)->pluck('name', 'id')->toArray();
        $clientCities = City::toBase()->whereStateId($client->state_id)->pluck('name', 'id')->toArray();
        $vatNoLabel = getVatNoLabel();

        return view('clients.edit', compact('client', 'countries', 'clientState', 'clientCities','vatNoLabel'));
    }

    public function update($clientId, UpdateClientRequest $request): RedirectResponse
    {
        $client = Client::withoutGlobalScope(new TenantScope())->whereId($clientId)->first();
        $input = $request->all();
        $this->clientRepository->updateClient($input, $client);
        Flash::success(__('messages.flash.client_updated'));

        return redirect()->route('clients.index');
    }

    public function destroy($clientId,Request $request): JsonResponse
    {
        $check = $request->get('clientWithInvoices');
        $clientTenant = TenantWiseClient::find($clientId);
        $client = Client::whereId($clientTenant->client_id)->withoutGlobalScope(new TenantScope())->first();

        if (! $client) {
            return $this->sendError('Seems, you are not allowed to access this record.');
        }
        $invoiceModels = [
            Invoice::class,
        ];

        $result = canDelete($invoiceModels, 'client_id', $client->id);
        if ($check && $result) {
            return $this->sendError(__('messages.flash.client_cant_deleted'));
        }

        $clientTenant->delete();
        $client->invoices()->delete();

        return $this->sendSuccess(__('messages.flash.client_Deleted'));
    }

    public function getStates(Request $request): mixed
    {
        $countryId = $request->get('countryId');
        $states = getStates($countryId);

        return $this->sendResponse($states, __('messages.flash.states_retrieved'));
    }

    public function getCities(Request $request): mixed
    {
        $stateId = $request->get('stateId');
        $cities = getCities($stateId);

        return $this->sendResponse($cities, __('messages.flash.cities_retrieved'));
    }
}

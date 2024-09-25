<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Repositories\AccountRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AccountController extends AppBaseController
{
    /** @var AccountRepository */
    public $accountRepository;

    public function __construct(AccountRepository $accountRepo)
    {
        $this->accountRepository = $accountRepo;
    }

    public function index(): \Illuminate\View\View
    {
        return view('accounts.index');
    }

    public function store(CreateAccountRequest $request)
    {
        $input = $request->all();
        $this->accountRepository->store($input);

        return $this->sendSuccess(__('messages.flash.account_saved'));
    }

    public function edit(Account $account)
    {
        return $this->sendResponse($account, 'messages.flash.account_retrived');
    }

    public function update(UpdateAccountRequest $request, $accountId)
    {
        $input = $request->all();
        $this->accountRepository->update($input, $accountId);

        return $this->sendSuccess(__('messages.flash.account_updated'));
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return $this->sendSuccess(__('messages.flash.account_deleted'));
    }
}

<?php

namespace App\Repositories;

use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class AccountRepository
 */
class AccountRepository extends BaseRepository
{
    public $fieldSearchable = [
        'Holder_name',
        'bank_name',
        'account_number',
        'balance',
    ];

    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * {@inheritDoc}
     */
    public function model()
    {
        return Account::class;
    }

    public function store($input)
    {
        try {
            DB::beginTransaction();
            Account::create($input);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}

<?php

namespace App\Repositories;

use App\Models\AdminCurrency;

/**
 * Class SuperAdminCurrencyRepository
 */
class AdminCurrencyRepository extends BaseRepository
{
    public $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AdminCurrency::class;
    }
}

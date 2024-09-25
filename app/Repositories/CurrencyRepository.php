<?php

namespace App\Repositories;

use App\Models\Currency;

/**
 * Class CurrencyRepository
 */
class CurrencyRepository extends BaseRepository
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
        return Currency::class;
    }
}

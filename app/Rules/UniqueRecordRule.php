<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniqueRecordRule implements Rule
{
    /**
     * The table to run the query against.
     *
     * @var string
     */
    protected $table;

    /**
     * The column to check on.
     *
     * @var string
     */
    protected $column;

    protected $updateFieldValue;

    public function __construct($table, $column, $updateFieldValue = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->updateFieldValue = $updateFieldValue;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        $query = DB::table($this->table)
            ->where($this->column, $value)
            ->where('tenant_id', Auth::user()->tenant_id);

        if (! empty($this->updateFieldValue)) {
            $query->where('id', '!=', $this->updateFieldValue);
        }

        $exists = $query->exists();

        return ($exists) ? false : true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): array
    {
        return [];
    }
}

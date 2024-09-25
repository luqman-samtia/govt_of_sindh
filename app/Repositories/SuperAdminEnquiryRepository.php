<?php

namespace App\Repositories;

use App\Models\SuperAdminEnquiry;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class SuperAdminEnquiryRepository
 *
 * @version February 13, 2020, 8:55 am UTC
 */
class SuperAdminEnquiryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'first_name',
        'last_name',
        'phone',
        'message',
        'status',
    ];

    /**
     * Return searchable fields
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SuperAdminEnquiry::class;
    }

    public function store(array $input): bool
    {
        try {
            SuperAdminEnquiry::create($input);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }
}

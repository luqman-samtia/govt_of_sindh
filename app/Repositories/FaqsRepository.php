<?php

namespace App\Repositories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FaqsRepository
 */
class FaqsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'question',
        'answer',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Faq::class;
    }

    public function store($input)
    {
        $faqs = Faq::create($input);

        return $faqs;
    }

    public function updateFaqs(array $input, $faqs)
    {
        $faqs->update($input);

        return $faqs;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFaqsRequest;
use App\Models\Faq;
use App\Repositories\FaqsRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FaqController extends AppBaseController
{
    /**
     * @var FaqsRepository
     */
    private $faqsRepo;

    public function __construct(FaqsRepository $faqsRepository)
    {
        $this->faqsRepo = $faqsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\View\View
    {
        return view('landing.faqs.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFaqsRequest $request): JsonResponse
    {
        $input = $request->all();
        $totalFAQs = Faq::count();
        if ($totalFAQs != 5) {
            $this->faqsRepo->store($input);

            return $this->sendSuccess(__('messages.flash.FAQs_created'));
        } else {
            return $this->sendError(__('messages.flash.cannot_create_more_than_five'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $faqs = Faq::findOrFail($id);

        return $this->sendResponse($faqs, __('messages.flash.FAQs_retrieved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): JsonResponse
    {
        $faqs = Faq::findOrFail($id);

        return $this->sendResponse($faqs, __('messages.flash.FAQs_retrieved'));
    }

    public function update(CreateFaqsRequest $request, Faq $faqs)
    {
        $input = $request->all();
        $this->faqsRepo->updateFaqs($input, $faqs);

        return $this->sendSuccess(__('messages.flash.FAQs_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $faqs = Faq::findOrFail($id);
        $faqs->delete();

        return $this->sendSuccess(__('messages.flash.FAQs_deleted'));
    }
}

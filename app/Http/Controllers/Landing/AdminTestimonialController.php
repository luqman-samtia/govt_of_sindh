<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateAdminTestimonialRequest;
use App\Models\AdminTestimonial;
use App\Repositories\SuperAdminTestimonialRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminTestimonialController extends AppBaseController
{
    /**
     * @var SuperAdminTestimonialRepository
     */
    private $adminTestimonialRepository;

    /**
     * TestimonialController constructor.
     */
    public function __construct(SuperAdminTestimonialRepository $testimonialRepository)
    {
        $this->adminTestimonialRepository = $testimonialRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     *
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\View\View
    {
        return view('landing.testimonial.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAdminTestimonialRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $this->adminTestimonialRepository->store($input);

            return $this->sendSuccess(__('messages.flash.testimonial_saved'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminTestimonial $adminTestimonial): JsonResponse
    {
        return $this->sendResponse($adminTestimonial, __('messages.flash.testimonial_retrieved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminTestimonial $adminTestimonial): JsonResponse
    {
        return $this->sendResponse($adminTestimonial, __('messages.flash.testimonial_retrieved'));
    }

    /**
     * @return mixed
     */
    public function update(CreateAdminTestimonialRequest $request, AdminTestimonial $adminTestimonial)
    {
        try {
            $this->adminTestimonialRepository->updateTestimonial($request->all(), $adminTestimonial->id);

            return $this->sendSuccess(__('messages.flash.testimonial_updated'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminTestimonial $adminTestimonial): JsonResponse
    {
        $adminTestimonial->delete();

        return $this->sendSuccess(__('messages.flash.testimonial_deleted'));
    }
}

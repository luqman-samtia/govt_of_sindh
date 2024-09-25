<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentQrCodeRequest;
use App\Http\Requests\UpdatePaymentQrCodeRequest;
use App\Models\PaymentQrCode;
use App\Repositories\PaymentQrCodeRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentQrCodeController extends AppBaseController
{
    /** @var PaymentQrCodeRepository */
    public $paymentQrCodeRepository;

    /**
     * @param  PaymentQrCodeRepository  $paymentqrcoderepo
     */
    public function __construct(PaymentQrCodeRepository $paymentQrCodeRepo)
    {
        $this->paymentQrCodeRepository = $paymentQrCodeRepo;
    }

    /**
     *
     * @throws Exception
     */
    public function index(): \Illuminate\View\View
    {
        return view('payment_qr_codes.index');
    }

    public function store(CreatePaymentQrCodeRequest $request)
    {
        $input = $request->all();
        $this->paymentQrCodeRepository->store($input);

        return $this->sendSuccess(__('messages.flash.payment_qr_code_saved'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(PaymentQrCode $paymentQrCode)
    {
        return $this->sendResponse($paymentQrCode, 'messages.flash.payment_qr_code_retrived');
    }

    public function update(UpdatePaymentQrCodeRequest $request, PaymentQrCode $paymentQrCode)
    {
        $input = $request->all();
        $this->paymentQrCodeRepository->update($input, $paymentQrCode);

        return $this->sendSuccess(__('messages.flash.payment_qr_code_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(PaymentQrCode $paymentQrCode)
    {
        $paymentQrCode->delete();

        return $this->sendSuccess(__('messages.flash.payment_qr_code_deleted'));
    }

    public function defaultStatus(PaymentQrCode $paymentQrCode)
    {
        $status = !$paymentQrCode->is_default;

        PaymentQrCode::query()->update(['is_default' => 0]);
        $paymentQrCode->update(['is_default' => $status]);

        return $this->sendSuccess(__('messages.flash.payment_qr_code_status_updated'));
    }
}

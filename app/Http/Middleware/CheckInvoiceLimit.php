<?php

namespace App\Http\Middleware;

use App\Models\Invoice;
use App\Utils\ResponseUtil;
use Closure;
use Illuminate\Http\Request;
use Response;

class CheckInvoiceLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        $invoiceCount = Invoice::count();
        $invoiceLimit = currentActiveSubscription()->subscriptionPlan->invoice_limit;
        if (! ($invoiceLimit > $invoiceCount)) {
            return Response::json(ResponseUtil::makeError(__('messages.flash.invoice_update_your_subscription')), 422);
        }

        return $next($request);
    }
}

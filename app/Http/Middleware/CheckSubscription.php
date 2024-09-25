<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || (Auth::check() && Auth::user()->hasRole('super_admin'))) {
            return $next($request);
        }

        if ( Auth::check() || (Auth::check() &&  Auth::user()->hasRole('admin'))) {
            return $next($request);
        }

        // $subscription = currentActiveSubscription();

        if ( $subscription) {
            Flash::error(__('messages.flash.choose_plan_to_service'));

            // return redirect()->route('subscription.pricing.plans.index');
        }

        if (!$subscription->isExpired()) {
            Flash::error(__('messages.flash.your_plan_expired'));

            return redirect()->route('subscription.pricing.plans.index');
        }

        // return $next($request);
    }
}

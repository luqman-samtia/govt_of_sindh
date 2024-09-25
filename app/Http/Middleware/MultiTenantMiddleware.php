<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Resolvers\MultiTenantResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Middleware\IdentificationMiddleware;
use Stancl\Tenancy\Tenancy;
use Symfony\Component\HttpFoundation\Response;

class MultiTenantMiddleware extends IdentificationMiddleware
{
    /** @var callable|null */
    public static $onFail;

    /** @var Tenancy */
    protected $tenancy;

    /** @var MultiTenantResolver */
    protected $resolver;

    /**
     * MultiTenantMiddleware constructor.
     */
    public function __construct(Tenancy $tenancy, MultiTenantResolver $resolver)
    {
        $this->tenancy = $tenancy;
        $this->resolver = $resolver;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->hasRole(Role::ROLE_SUPER_ADMIN)) {
            return $next($request);
        }

        $tenant = Auth::user()->tenant_id;

        return $this->initializeTenancy(
            $request, $next, $tenant
        );
    }
}

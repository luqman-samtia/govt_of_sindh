@role('super_admin')
    <li class="nav-item {{ Request::is('super-admin/dashboard*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('super.admin.dashboard') }}">
            <span class="menu-icon">
                <i class="fa-solid fa-chart-pie pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.dashboard') }}</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('super-admin/super-admins*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('super-admins.index') }}">
            <span class="menu-icon">
                <i class="fas fa-user pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.super_admins') }}</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('super-admin/users*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('users.index') }}">
            <span class="menu-icon">
                <i class="fas fa-users pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.users') }}</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('super-admin/forms*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('users.forms') }}">
            <span class="menu-icon">
                <i class="far fa-file-alt pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('Users Forms') }}</span>
        </a>
    </li>

    {{-- <li class="nav-item {{ Request::is('super-admin/subscription-plan*', 'super-admin/transactions*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page"
            href="{{ route('subscription-plans.index') }}">
            <span class="menu-icon">
                <i class="fas fa-rupee-sign pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.subscription_plan') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('super-admin/enquiries*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page"
            href="{{ route('super.admin.enquiry.index') }}">
            <span class="menu-icon">
                <i class="fab fa-elementor pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.enquiries') }}</span>
        </a>
    </li> --}}

    {{-- Subscribers --}}
    {{-- <li class="nav-item {{ Request::is('super-admin/subscribers*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page"
            href="{{ route('super.admin.subscribe.index') }}">
            <span class="menu-icon">
                <i class="fab fa-stripe-s pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.subscribe.subscribers') }}</span>
        </a>
    </li> --}}

    {{-- Landing Screen Section One --}}
    {{-- <li
        class="nav-item {{ Request::is('super-admin/section-one*', 'super-admin/section-two*', 'super-admin/section-three*', 'super-admin/admin-testimonial*', 'super-admin/faqs*', 'super-admin/cookie-warning*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page"
            href="{{ route('super.admin.section.one') }}">
            <span class="menu-icon">
                <i class="fas fa fa-cog pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.landing_cms.landing_cms') }}</span>
        </a>
    </li> --}}

    {{-- Settings --}}
    {{-- <li
        class="nav-item {{ Request::is('super-admin/general-settings*', 'super-admin/currencies*', 'super-admin/footer-settings*', 'super-admin/new-user-settings') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page"
            href="{{ route('super.admin.settings.edit') }}">
            <span class="menu-icon">
                <i class="fa fa-cogs pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.settings') }}</span>
            <span class="d-none">{{ __('messages.general') }}</span>
            <span class="d-none">{{ __('messages.sidebar_setting') }}</span>
        </a>
    </li> --}}
@endrole
@role('admin')
    <li class="nav-item {{ Request::is('admin/dashboard*', 'admin/currency-reports*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('admin.dashboard') }}">
            <span class="menu-icon">
                <i class="fa-solid fa-chart-pie pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.dashboard') }}</span>
        </a>
    </li>

    {{-- <li class="nav-item {{ Request::is('admin/client*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('clients.index') }}">
            <span class="menu-icon">
                <i class="fas fa-user-alt pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.clients') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/categories*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('category.index') }}">
            <span class="menu-icon">
                <i class="fas fa-th-list pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.categories') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/taxes*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('taxes.index') }}">
            <span class="menu-icon">
                <i class="fas fa-percentage pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.taxes') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/products*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('products.index') }}">
            <span class="menu-icon">
                <i class="fas fa-cube pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.products') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/quotes*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('quotes.index') }}">
            <span class="menu-icon">
                <i class="fa-solid fas fa-quote-left pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.quotes') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/invoices*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('invoices.index') }}">
            <span class="menu-icon">
                <i class="far fa-file-alt pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.invoices') }}</span>
        </a>
    </li> --}}
    <li class="nav-item {{ Request::is('admin/forms*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="">
            <span class="menu-icon">
                <i class="far fa-file-alt pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('form') }}</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('profile/edit*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('profile.setting') }}">
            <span class="dropdown-icon me-4 text-gray-600">
                <i class="fa-solid fa-user"></i>
            </span>
            <span class="aside-menu-title">{{ __('Update Profile') }}</span>
        </a>
    </li>

    {{-- <li class="nav-item {{ Request::is('admin/payment-qr-codes*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page"
            href="{{ route('payment-qr-codes.index') }}">
            <span class="menu-icon">
                <i class="fa-solid fa-qrcode pe-3"></i>
            </span>
            <span class="aside-menu-title">{!! __('messages.payment_qr_codes.payment_qr_codes') !!}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/transactions*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('transactions.index') }}">
            <span class="menu-icon">
                <i class="fas fa-list-ol pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.transactions') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/accounts*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('accounts.index') }}">
            <span class="menu-icon">
                <i class="fa-solid fa-building-columns pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.accounts.accounts') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/payments*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('payments.index') }}"
            data-turbo="false">
            <span class="menu-icon">
                <i class="fas fa-money-check pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.payments') }}</span>
        </a>
    </li> --}}

    {{-- <li class="nav-item {{ Request::is('admin/template-setting*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('invoiceTemplate') }}">
            <span class="menu-icon">
                <i class="far fa-copy pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.invoice_templates') }}</span>
        </a>
    </li> --}}

    {{-- <li
        class="nav-item {{ Request::is('admin/settings*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('settings.edit') }}">
            <span class="menu-icon">
                <i class="fa fa-cogs pe-3"></i>
            </span>
            <span class="aside-menu-title">{{ __('messages.settings') }}</span>
        </a>
    </li> --}}
@endrole

@role('client')
    @include('client_panel.layouts.menu')
@endrole

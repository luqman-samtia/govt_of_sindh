@php
    $notifications = getNotification();
    $notificationCount = count($notifications);
    $styleCss = 'style';
@endphp
<header class='d-flex align-items-center justify-content-between flex-grow-1 header px-3 px-xl-0' >
    <button id="gos_bg_color" type="button" class="btn px-0 aside-menu-container__aside-menubar d-block d-xl-none sidebar-btn">
        <i id="gos_bg_color" class="fa-solid fa-bars fs-1"></i>
    </button>
    <nav class="navbar navbar-expand-xl navbar-light top-navbar d-xl-flex d-block px-3 px-xl-0 py-4 py-xl-0 "
        id="nav-header">
        <div class="container-fluid pe-0">
            @role('admin')
                <div class="d-sm-flex d-none align-items-stretch dropdown me-2">

                    {{-- <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0"
                        type="button" data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false"
                        id="quickLinksID">
                        <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ __('messages.quick_links') }}">
                            <i class="fas fa-plus"></i>
                        </span>
                    </button> --}}
                    <div x-placement="bottom-start" aria-labelledby="quickLinksID"
                        class="shortcut-menu dropdown-menu px-3 py-3" data-popper-reference-hidden="false"
                        data-popper-escaped="false" data-popper-placement="bottom-start"
                        style="position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 44px);">

                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('invoices.index') }}">
                            <a class="nav-link px-4" href="{{ route('invoices.index') }}">
                                <span class="dropdown-icon me-4">
                                    <i class="fa-solid far fa-file-alt pe-2"></i>
                                </span>
                                <span>{{ __('messages.invoices') }}</span>
                            </a>
                        </a> --}}
                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('quotes.index') }}">
                            <a class="nav-link px-4" href="{{ route('quotes.index') }}">
                                <span class="dropdown-icon me-4">
                                    <i class="fa-solid fas fa-quote-left pe-2"></i>
                                </span>
                                <span>{{ __('messages.quotes') }}</span>
                            </a>
                        </a> --}}
                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('products.index') }}">
                            <a class="nav-link px-4" href="{{ route('products.index') }}">
                                <span class="dropdown-icon me-4">
                                    <i class="fa-solid fas fa-cube pe-2"></i>
                                </span>
                                <span>{{ __('messages.products') }}</span>
                            </a>
                        </a> --}}
                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('taxes.index') }}">
                            <a class="nav-link px-4" href="{{ route('taxes.index') }}">
                                <span class="dropdown-icon me-4">
                                    <i class="fa-solid fas fa-percentage pe-2"></i>
                                </span>
                                <span>{{ __('messages.taxes') }}</span>
                            </a>
                        </a> --}}
                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('clients.index') }}">
                            <a class="nav-link px-4" href="{{ route('clients.index') }}">
                                <span class="dropdown-icon me-3">
                                    <i class="fa-solid fas fa-users pe-2"></i>
                                </span>
                                <span>{{ __('messages.clients') }}</span>
                            </a>
                        </a> --}}
                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('payment-qr-codes.index') }}">
                            <a class="nav-link px-4" href="{{ route('payment-qr-codes.index') }}">
                                <span class="dropdown-icon me-4">
                                    <i class="fa-solid fa-qrcode pe-2"></i>
                                </span>
                                <span>{{ __('messages.payment_qr_codes.payment_qr_codes') }}</span>
                            </a>
                        </a> --}}
                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('transactions.index') }}">
                            <a class="nav-link px-4" href="{{ route('transactions.index') }}">
                                <span class="dropdown-icon me-3">
                                    <i class="fa-solid fas fa-list-ol pe-2"></i>
                                </span>
                                <span>{{ __('messages.transactions') }}</span>
                            </a>
                        </a> --}}
                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('accounts.index') }}">
                            <a class="nav-link px-4" href="{{ route('accounts.index') }}">
                                <span class="dropdown-icon me-4">
                                    <i class="fa-solid fa-building-columns pe-2"></i>
                                </span>
                                <span>{{ __('messages.accounts.accounts') }}</span>
                            </a>
                        </a> --}}
                        {{-- <a class="py-0 fs-4 dropdown-item" href="{{ route('payments.index') }}">
                            <a class="nav-link px-4" href="{{ route('payments.index') }}">
                                <span class="dropdown-icon me-3">
                                    <i class="fa-solid fas fa-money-check pe-2"></i>
                                </span>
                                <span>{{ __('messages.payments') }}</span>
                            </a>
                        </a> --}}
                    </div>

                </div>

            @endrole
            <div class="navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @include('layouts.sub_menu')
                </ul>
            </div>
        </div>
    </nav>
    <ul class="nav align-items-center">
        @hasrole('admin')
            {{-- <li class="px-xxl-3 px-2">
                <a class="btn btn-primary createInvoiceBtn nowrap" href="{{ route('invoices.create') }}"
                    data-turbo="false">{{ __('messages.invoice.new_invoice') }}</a>
            </li> --}}
        @endrole
        @hasrole('client')
            @if (count(getLoginTenantNames()) > 1)
                <li class="px-xxl-3 px-2">
                    <div class="switch-tenant-wise-client">
                        {{ Form::select('client', getLoginTenantNames(), getLogInUser()->tenant_id, ['class' => 'form-control change-tenant-client', 'data-control' => 'select2']) }}
                    </div>
                </li>
            @endif
        @endrole
        <li class="px-xxl-3 px-2">
            @if (Auth::user()->dark_mode)
                <a  href="javascript:void(0)" data-turbo="false" title="{{ __('messages.switch_to_light_mode') }}">
                    <i style="color: #48B7A3 !important;" class="fa-solid fa-moon text-primary fs-2 apply-dark-mode"></i>
                </a>
            @else
                <a  href="javascript:void(0)" data-turbo="false" title="{{ __('messages.switch_to_dark_mode') }}">
                    <i style="color: #48B7A3 !important;"  class="fa-solid fa-sun text-info fs-2 apply-dark-mode"></i></a>
            @endif
        </li>
        <li class="px-xxl-3 px-2">
            <div class="dropdown custom-dropdown d-flex align-items-center py-4">
                {{-- <button  class="btn dropdown-toggle hide-arrow p-0 position-relative" type="button"
                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i style="color: #48B7A3 !important;" class="fa-solid fa-bell text-primary fs-2"></i>
                    @if (count(getNotification()) != 0)
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge badge-circle bg-danger notification-count"
                            id="counter">
                            {{ count(getNotification()) }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </button> --}}
                <div class="dropdown-menu py-0 my-2" aria-labelledby="dropdownMenuButton1">
                    <div class="text-start border-bottom py-4 px-7">
                        <h3 class="text-gray-900 mb-0">{{ __('messages.notification.notifications') }}</h3>
                    </div>
                    <div class="px-7 mt-5 inner-scroll height-270">
                        @if ($notificationCount > 0)
                            @foreach ($notifications as $notification)
                                <a data-turbo="false" href="#" data-id="{{ $notification->id }}"
                                    class="notification text-hover-primary text-decoration-none" id="notification">

                                    <div class="d-flex position-relative mb-5 text-hover-primary">
                                        @php
                                            $datework = Carbon\Carbon::parse($notification->created_at);
                                            $now = Carbon\Carbon::now();
                                            $diff = $datework->diffForHumans($now);
                                        @endphp
                                        <span class="me-5 text-primary fs-2 icon-label"><i
                                                class="fa-solid {{ getNotificationIcon($notification->type) }}"></i></span>
                                        <div>
                                            <h5 class="text-gray-900 fs-6 mb-2">{{ $notification->title }}</h5>
                                            <h6 class="text-gray-600 fs-small fw-light mb-0">{{ $diff }}</h6>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="empty-state fs-6 text-gray-800 fw-bold text-center mt-5" data-height="400">
                                <p>{{ __('messages.notification.you_don`t_have_any_new_notification') }}</p>
                            </div>
                        @endif
                        <div class="empty-state fs-6 text-gray-800 fw-bold text-center mt-5 d-none" data-height="400">
                            <p>{{ __('messages.notification.you_don`t_have_any_new_notification') }}</p>
                        </div>
                    </div>
                    @if (count(getNotification()) > 0)
                        <div class="text-center border-top p-4">
                            <a href="#"
                                class="read-all-notification text-primary mb-0 fs-5 text-decoration-none"
                                id="readAllNotification">
                                {{ __('messages.notification.mark_all_as_read') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </li>
        <li class="px-xxl-3 px-2">
            <div class="dropdown d-flex align-items-center py-4">
                <div class="image image-circle image-mini">
                    <img src="{{ getLogInUser()->profile_image }}" class="img-fluid" alt="profile image">
                </div>
                <button class="btn dropdown-toggle ps-2 pe-0" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    {{ getLogInUser()->full_name }}
                </button>
                <div class="dropdown-menu py-7 pb-4 my-2" aria-labelledby="dropdownMenuButton1">
                    <div class="text-center border-bottom pb-5">
                        <div class="image image-circle image-tiny mb-5">
                            <img src="{{ getLogInUser()->profile_image }}" class="img-fluid" alt="profile image">
                        </div>
                        <h3 class="text-gray-900">{{ getLogInUser()->full_name }}</h3>
                        <h4 class="mb-0 fw-400 fs-6">{{ getLogInUser()->email }}</h4>
                    </div>
                    <ul class="pt-4">
                        <li>
                            <a class="dropdown-item text-gray-900" href="{{ route('profile.setting') }}">
                                <span class="dropdown-icon me-4 text-gray-600">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                {{ __('messages.user.account_setting') }}
                            </a>
                        </li>
                        @if (getLoggedInUser()->hasRole('admin'))
                            {{-- <li>
                                <a class="dropdown-item text-gray-900"
                                    href="{{ route('subscription.pricing.plans.index') }}">
                                    <span class="dropdown-icon me-4 text-gray-600">
                                        <i class="fa-solid fa-money-bill"></i>
                                    </span>
                                    {{ __('messages.subscription_plans.subscription_plans') }}
                                </a>
                            </li> --}}
                        @endif
                        @if (!session('impersonated_by'))
                            <li>
                                <a class="dropdown-item text-gray-900" id="changePassword">
                                    <span class="dropdown-icon me-4 text-gray-600">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    {{ __('messages.user.change_password') }}
                                </a>
                            </li>
                        @endif
                        @if (session('impersonated_by'))
                            <li>
                                {{-- <a class="dropdown-item text-gray-900 position-relative cursor-pointer"
                                    href="{{ route('impersonate.userLogout') }}">
                                    <span class="dropdown-icon me-4 text-gray-600">
                                        <i class="fa fa-external-link"></i>
                                    </span>
                                    {{ __('messages.back_to_super_admin') }}
                                </a> --}}
                            </li>
                        @endif
                        <li>
                            {{-- <a class="dropdown-item text-gray-900 position-relative cursor-pointer"
                                id="changeLanguage" href="javascript:void(0)">
                                <span class="dropdown-icon me-4 text-gray-600">
                                    <i class="fa-solid fa-globe"></i>
                                </span>
                                {{ __('messages.user.change_language') }}
                            </a> --}}
                        </li>
                        @if (!session('impersonated_by'))
                            <li>
                                <a class="dropdown-item text-gray-900 d-flex cursor-pointer">
                                    <span class="dropdown-icon me-4 text-gray-600">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </span>
                                    <form id="logout-form" action="{{ route('logout') }}" method="post">
                                        @csrf
                                    </form>
                                    <span
                                        onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                                        {{ __('messages.sign_out') }}</span>
                                </a>

                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </li>
        <li>
            <button type="button" class="btn px-0 d-block d-xl-none header-btn pb-2">
                <i class="fa-solid fa-bars fs-1"></i>
            </button>
        </li>
    </ul>
</header>
<div class="bg-overlay" id="nav-overly"></div>

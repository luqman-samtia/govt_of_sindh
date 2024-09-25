<li
    class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('*/dashboard*', '*/currency-reports*') ? 'd-none' : '' }}">
    @hasrole('admin')
        <a class="nav-link p-0 {{ Request::is('*/dashboard*') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}
        </a>
    @endrole
    @hasrole('client')
        <a class="nav-link p-0 {{ Request::is('*/dashboard*') ? 'active' : '' }}"
            href="{{ route('client.dashboard') }}">{{ __('messages.dashboard') }}</a>
    @endrole
</li>
<li
    class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('*/dashboard*', '*/currency-reports*') ? 'd-none' : '' }}">
    @role('admin')
        {{-- <a class="nav-link p-0 {{ Request::is('*/currency-reports*') ? 'active' : '' }}"
            href="{{ route('currency.reports') }}">{{ __('messages.currency_reports') }}
        </a> --}}
    @endrole
    {{-- @role('client')
        <a class="nav-link p-0 {{ Request::is('*/currency-reports*') ? 'active' : '' }}"
            href="{{ route('client.currency.reports') }}">{{ __('messages.currency_reports') }}
        </a>
    @endrole --}}
</li>
@role('super_admin')
    {{-- Super Admin Dashboard Sub Menu --}}
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/dashboard*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/dashboard*') ? 'active' : '' }}"
            href="{{ route('super.admin.dashboard') }}">{{ __('messages.dashboard') }}</a>
    </li>

    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/super-admins*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/super-admins*') ? 'active' : '' }}"
            href="{{ route('super-admins.index') }}">{{ __('messages.super_admins') }}</a>
    </li>

    <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/users*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/users*') ? 'active' : '' }}"
            href="{{ route('users.index') }}">{{ __('messages.users') }}</a>
    </li>

    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/enquiries*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/enquiries*') ? 'active' : '' }}"
            href="{{ route('super.admin.enquiry.index') }}">{{ __('messages.enquiries') }}</a>
    </li>

    {{-- Super Admin Landing CMS Section One Sub Menu --}}
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/section-one*', 'super-admin/section-two*', 'super-admin/section-three*', 'super-admin/faqs*', 'super-admin/admin-testimonial*', 'super-admin/cookie-warning*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/section-one*') ? 'active' : '' }}"
            href="{{ route('super.admin.section.one') }}">{{ __('messages.landing_cms.section_one') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/section-one*', 'super-admin/section-two*', 'super-admin/section-three*', 'super-admin/faqs*', 'super-admin/admin-testimonial*', 'super-admin/cookie-warning*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/section-two*') ? 'active' : '' }}"
            href="{{ route('super.admin.section.two') }}">{{ __('messages.landing_cms.section_two') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/section-one*', 'super-admin/section-two*', 'super-admin/section-three*', 'super-admin/faqs*', 'super-admin/admin-testimonial*', 'super-admin/cookie-warning*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/section-three*') ? 'active' : '' }}"
            href="{{ route('super.admin.section.three') }}">{{ __('messages.landing_cms.section_three') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/section-one*', 'super-admin/section-two*', 'super-admin/section-three*', 'super-admin/faqs*', 'super-admin/admin-testimonial*', 'super-admin/cookie-warning*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/faqs*') ? 'active' : '' }}"
            href="{{ route('faqs.index') }}">{{ __('messages.faqs.faqs') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/section-one*', 'super-admin/section-two*', 'super-admin/section-three*', 'super-admin/faqs*', 'super-admin/admin-testimonial*', 'super-admin/cookie-warning*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/admin-testimonial*') ? 'active' : '' }}"
            href="{{ route('admin-testimonial.index') }}">{{ __('messages.testimonials') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/section-one*', 'super-admin/section-two*', 'super-admin/section-three*', 'super-admin/faqs*', 'super-admin/admin-testimonial*', 'super-admin/cookie-warning*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/cookie-warning*') ? 'active' : '' }}"
            href="{{ route('cookie.warning.index') }}">{{ __('messages.cookie_warning') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/subscriber*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/subscriber*') ? 'active' : '' }}"
            href="{{ route('super.admin.subscribe.index') }}">{{ __('messages.subscribe.subscribers') }}</a>
    </li>
@endrole
{{-- <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/clients*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/clients*') ? 'active' : '' }}"
        href="{{ route('clients.index') }}">{{ __('messages.clients') }}</a>
</li> --}}

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('subscription-plans*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('subscription-plans*') ? 'active' : '' }}"
        href="{{ route('subscription.pricing.plans.index') }}">{{ __('messages.subscription_plan') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('choose-payment-type*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('choose-payment-type*') ? 'active' : '' }}"
        href="{{ route('subscription.pricing.plans.index') }}">{{ __('messages.subscription_plan') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/categories*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/categories*') ? 'active' : '' }}"
        href="{{ route('category.index') }}">{{ __('messages.categories') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/taxes*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/taxes*') ? 'active' : '' }}"
        href="{{ route('taxes.index') }}">{{ __('messages.taxes') }}</a>
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/products*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/products*') ? 'active' : '' }}"
        href="{{ route('products.index') }}">{{ __('messages.products') }}</a>
</li>

@role('admin')

    <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/transactions*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('admin/transactions*') ? 'active' : '' }}"
            href="{{ route('transactions.index') }}">{{ __('messages.transactions') }}</a>
    </li>
@else
    <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('client/transactions*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('client/transactions*') ? 'active' : '' }}"
            href="{{ route('client.transactions.index') }}">{{ __('messages.transactions') }}</a>
    </li>

@endrole
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('*/quotes*') ? 'd-none' : '' }}">
    @role('admin')
        <a class="nav-link p-0 {{ Request::is('*/quotes*') ? 'active' : '' }}"
            href="{{ route('quotes.index') }}">{{ __('messages.quotes') }}</a>
    @endrole
    @role('client')
        <a class="nav-link p-0 {{ Request::is('*/quotes*') ? 'active' : '' }}"
            href="{{ route('client.quotes.index') }}">{{ __('messages.quotes') }}</a>
    @endrole
</li>

<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('*/invoices*') ? 'd-none' : '' }}">
    @role('admin')
        <a class="nav-link p-0 {{ Request::is('*/invoices*') ? 'active' : '' }}"
            href="{{ route('invoices.index') }}">{{ __('messages.invoices') }}</a>
    @endrole


    @role('client')
        <a class="nav-link p-0 {{ Request::is('*/invoices*') ? 'active' : '' }}"
            href="{{ route('client.invoices.index') }}">{{ __('messages.invoices') }}</a>
    @endrole
</li>
@role('admin')
    <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('*/payment-qr-codes*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('*/payment-qr-codes*') ? 'active' : '' }}"
            href="{{ route('payment-qr-codes.index') }}">{{ __('messages.payment_qr_codes.payment_qr_codes') }}</a>
    @endrole
</li>

{{-- @role('admin')
    <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('*/accounts*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('*/accounts*') ? 'active' : '' }}"
            href="{{ route('accounts.index') }}">{{ __('messages.accounts.accounts') }}</a>

    </li>
@endrole --}}

<li
    class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/settings*', 'admin/currencies*', 'admin/payment-gateway*', 'admin/invoice-settings*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ isset($sectionName) ? ($sectionName == 'general' ? 'active' : '') : '' }}"
        href="{{ route('settings.edit', ['section' => 'general']) }}">{{ __('messages.general') }}</a>
</li>

<li
    class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/settings*', 'admin/currencies*', 'admin/payment-gateway*', 'admin/invoice-settings*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/currencies*') ? 'active' : '' }}"
        href="{{ route('currencies.index') }}">{{ __('messages.setting.currencies') }}</a>
</li>

<li
    class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/settings*', 'admin/currencies*', 'admin/payment-gateway*', 'admin/invoice-settings*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/payment-gateway*') ? 'active' : '' }}"
        href="{{ route('payment-gateway.show') }}">{{ __('messages.setting.payment-gateway') }}</a>
</li>

<li
    class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/settings*', 'admin/currencies*', 'admin/payment-gateway*', 'admin/invoice-settings*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/invoice-settings*') ? 'active' : '' }}"
        href="{{ route('settings.invoice-settings') }}">{{ __('messages.setting.invoice_settings') }}</a>
</li>

<li
    class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/template-setting*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admin/template-setting*') ? 'active' : '' }}"
        href="{{ route('invoiceTemplate') }}">{{ __('messages.invoice_templates') }}</a>
</li>

@role('admin')
    {{-- <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admin/payments*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('admin/payments*') ? 'active' : '' }}"
            href="{{ route('payments.index') }}">{{ __('messages.payments') }}</a>
    </li> --}}
@endrole


@role('super_admin')
    {{-- Super Admin Subscription Sub Menu --}}
    {{-- <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/subscription-plans*', 'super-admin/transactions*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/subscription-plans*') ? 'active' : '' }}"
            href="{{ route('subscription-plans.index') }}">{{ __('messages.subscription_plans.subscription_plans') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/subscription-plans*', 'super-admin/transactions*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/transactions*') ? 'active' : '' }}"
            href="{{ route('subscriptions.transactions.index') }}">{{ __('messages.subscription_plans.transactions') }}</a>
    </li>

    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/general-settings*', 'super-admin/currencies*', 'super-admin/footer-settings*', 'super-admin/new-user-settings*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/general-settings*') ? 'active' : '' }}"
            href="{{ route('super.admin.settings.edit') }}">{{ __('messages.settings') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/general-settings*', 'super-admin/currencies*', 'super-admin/footer-settings*', 'super-admin/new-user-settings*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/currencies*') ? 'active' : '' }}"
            href="{{ route('super.admin.currencies.index') }}">{{ __('messages.setting.currencies') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/general-settings*', 'super-admin/currencies*', 'super-admin/footer-settings*', 'super-admin/new-user-settings*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/footer-settings*') ? 'active' : '' }}"
            href="{{ route('super.admin.footer.settings.edit') }}">{{ __('messages.footer_setting.footer_settings') }}</a>
    </li>
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('super-admin/general-settings*', 'super-admin/currencies*', 'super-admin/footer-settings*', 'super-admin/new-user-settings*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('super-admin/new-user-settings*') ? 'active' : '' }}"
            href="{{ route('new-user-settings.edit') }}">{{ __('messages.new_user_settings.new_user_settings') }}</a>
    </li> --}}
@endrole

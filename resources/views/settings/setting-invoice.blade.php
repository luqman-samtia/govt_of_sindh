@extends('settings.edit')
@section('title')
    {{ __('messages.invoice_templates') }}
@endsection
@section('section')
    <div class="card">
        <div class="card-body pt-0 fs-6 py-8 px-0 px-lg-10 text-gray-700">
            <div class="row g-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-4">
                    <div class="card card-xxl-stretch mb-xl-8">
                        <div class="card-body d-flex flex-column">
                            <div class="mt-5">
                                <div class="d-flex flex-stack mb-5">
                                    <div class="d-flex align-items-center me-2">
                                        {{ Form::open(['route' => 'invoiceTemplate.update', 'method' => 'post', 'id' => 'invoiceSetting', 'class' => 'invoice-settings']) }}
                                        <div class="row">
                                            <div class="col-lg-12 mb-5">
                                                {{ Form::label('invoice_template', __('messages.setting.invoice_template') . ':', ['class' => 'form-label mb-3']) }}
                                                <br>
                                                <select class="form-select form-select-solid" name="template"
                                                    id="invoiceTemplateId" data-control="select2">
                                                    @foreach ($invoiceTemplate as $template)
                                                        <option value="{{ $template['key'] }}"
                                                            data-color="{{ $template['template_color'] }}"
                                                            {{ $template['key'] == $defaultTemplate->value ? 'selected' : '' }}>
                                                            {{ $template['template_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-12 mb-5">
                                                {{ Form::label('invoice_color', __('messages.setting.color')) }}
                                                <div class="color-wrapper"></div>
                                                {{ Form::text('default_invoice_color', $invoiceTemplate[0]['template_color'] ?? null, ['id' => 'invoiceColor', 'hidden', 'class' => 'form-control']) }}
                                            </div>
                                            {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'id' => 'btnSave', 'class' => 'btn btn-primary save-btn-invoice col-6 ms-3', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <!--begin::Tables Widget 5-->
                    <div class="card card-xxl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span
                                    class="card-label fw-bolder fs-3 mb-1">{{ __('messages.setting.invoice_template') }}</span>
                            </h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3 px-md-3 px-0 bg-white">
                            <div class="tab-content">
                                <!--begin::Tap pane-->
                                <div class="tab-pane fade show active" id="">
                                    <div class="container px-sm-3 px-0">
                                        <div id="app" class="content pt-0 bg-white">
                                            <div class="editor">
                                                <div class="invoice-preview-inner w-auto">
                                                    <div class="editor-content" id="editorContent">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Tables Widget 5-->
                </div>
                <!--end::Col-->
            </div>
        </div>
    </div>
    {{ Form::hidden('company_address', 'Rajkot', ['id' => 'companyAddress']) }}
    {{ Form::hidden('company_phone_number', '+7405868976', ['id' => 'companyPhoneNumber']) }}
    {{ Form::hidden('company_name', getAppName(), ['id' => 'companyName']) }}
@endsection

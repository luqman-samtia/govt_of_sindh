<div class="row gx-10 mb-5">
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('name', __('messages.product.name').':', ['class' => 'form-label required mb-3']) }}
            <div class="btn btn-icon w-20px btn-sm">
            </div>
            <div class="input-group mb-5">
                {{ Form::text('name',isset($product) ? $product->name : null,['class' => 'form-control form-control-solid', 'placeholder' => __('messages.product.name'), 'required','onkeypress'=>"return blockSpecialChar(event)"]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('code', __('messages.product.code').':', ['class' => 'form-label required mb-3']) }}
            <div class="btn btn-icon w-20px btn-sm btn-active-color-primary me-1" data-bs-toggle="tooltip" title=""
                 data-placement="top"
                 data-bs-original-title="{{ __('messages.common.product_code_generate') }}">
                <i class="far fa-question-circle"></i>
            </div>
            <div class="input-group mb-5">
                {{ Form::text('code', $product->code ?? null,['class' => 'form-control form-control-solid', 'placeholder' => __('messages.product.code'), 'required','id' => 'code', 'maxlength' => 6,'onkeypress'=>"return blockSpecialChar(event)"]) }}
                <a class="input-group-text border-0" id="autoCode" href="javascript:void(0)" data-bs-toggle="tooltip"
                   data-placement="right" title="{{ __('messages.product.refresh_and_generate_code') }}">
                    <i class="fas fa-sync-alt fs-4"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('category', __('messages.product.category').':', ['class' => 'form-label required mb-3']) }}
            {{ Form::select('category_id', $categories,isset($product) ? $product->category_id : null,['class' => 'form-select form-select-solid', 'placeholder' => __('messages.product.category'), 'required', 'id'=>'adminCategoryId', 'data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('unit_price', __('messages.product.unit_price').':', ['class' => 'form-label required mb-3']) }}
            {{ Form::number('unit_price',isset($product) ? $product->unit_price : null,['class' => 'form-control form-control-solid', 'placeholder' => __('messages.product.unit_price'), 'min'=>'0', 'step'=>".01", 'oninput'=>"validity.valid||(value=value.replace(/\D+/g, '.'))",'required']) }}
        </div>
    </div>
    <div class="col-lg-12">
        <div class="mb-5">
            {{ Form::label('description', __('messages.product.description').':', ['class' => 'form-label mb-3']) }}
            {{ Form::textarea('description',isset($product) ? $product->description : null,['class' => 'form-control form-control-solid','rows' => '4', 'placeholder' => __('messages.product.description')]) }}
        </div>
    </div>
    <div class="col-lg-3 mb-7">
        <div class="mb-3" io-image-input="true">
            <label for="exampleInputImage" class="form-label">{{ __('messages.product.image').':' }}</label>
            <div class="d-block">
                <div class="image-picker">
                    <div class="image previewImage" id="productImage"
                    {{ $styleCss }}="background-image: url('{{ !empty($product->product_image) ? $product->product_image :
                        asset('assets/images/default-product.jpg') }}')">
                </div>
                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                      title="{{ __('messages.common.edit') }}">
                    <label>
                        <i class="fa-solid fa-pen" id="productImage"></i>
                            <input type="file" id="productImage" name="image" class="image-upload d-none"
                                   accept="image/*"/>
                    </label>
                </span>
            </div>
        </div>
        <div class="form-text">{{ __('messages.user.allowed_file_types') }}</div>
    </div>
</div>
<div class="d-flex justify-content-end">
    {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-2']) }}
    <a href="{{ route('products.index') }}" type="reset"
       class="btn btn-secondary btn-active-light-primary">{{__('messages.common.cancel')}}</a>
</div>

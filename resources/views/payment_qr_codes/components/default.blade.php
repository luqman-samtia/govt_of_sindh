<label class="form-switch form-check-custom  form-switch-sm justify-content-center">
    <input name="status" data-id="{{ $row->id }}" class="form-check-input qr-status" type="checkbox" value="1"
        {{ $row->is_default == 0 ? '' : 'checked' }}>
    <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
</label>

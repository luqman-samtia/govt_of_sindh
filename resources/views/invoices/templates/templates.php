<script id="invoiceItemTemplate" type="text/x-jsrender">
<tr class="border-bottom border-bottom-dashed tax-tr">
    <td class="text-center item-number align-center">1</td>
    <td class="table__item-desc w-25">
        <select class="form-select productId product form-select-solid fw-bold" name="product_id[]" 'data-control' => 'select2' required>
            <option selected="selected" value="">Select Product or Enter free text</option>
            {{for products}}
                <option value="{{:key}}">{{:value}}</option>
            {{/for}}
        </select>
    </td>
    <td class="table__qty">
        <input class="form-control qty form-control-solid" required="" name="quantity[]" type="number" min="0" step=".01" oninput="validity.valid||(value=value.replace(/[e\+\-]/gi,''))">
    </td>
    <td>
        <input class="form-control price-input price form-control-solid" required="" name="price[]" type="number" oninput="validity.valid||(value=value.replace(/[e\+\-]/gi,''))" min='0' step='.01' onKeyPress="if(this.value.length==8) return false;">
    </td>
    <td class="">
        <select data-link="defaultTax" class="form-select taxId tax form-select-solid fw-bold" name="tax[]" placeholder="Select Tax" multiple="multiple">
            {{for taxes}}
               <option value="{{:value}}" data-id="{{:id}}" {{:is_default ? 'selected' : '' }}>{{:name}}</option>
            {{/for}}
        </select>
    </td>
    <td class="tbAmount text-right item-total pt-8 text-nowrap">

    </td>
    <td class="text-end">
        <button type="button" data-bs-toggle="tooltip" title="Delete" class="btn btn-sm text-danger fs-3 btn-icon btn-active-color-danger delete-invoice-item">
                <i class="fa-solid fa-trash"></i>
        </button>
    </td>
</tr>



</script>

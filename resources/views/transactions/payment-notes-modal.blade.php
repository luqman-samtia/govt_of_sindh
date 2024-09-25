<div id="paymentNotesModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.payment.transaction_notes') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y">
                <div class="form-group col-sm-12 transaction-content">
                    <p id="showClientNotesId"></p>
                </div>
            </div>
        </div>
    </div>
</div>

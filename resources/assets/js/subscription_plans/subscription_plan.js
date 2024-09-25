    listenClick('#resetFilter', function () {
        $('#planTypeFilter').val('').trigger('change')
        livewire.emit('refreshDatatable');
    })

    listenClick('.plan-delete-btn', function (event) {
        let subscriptionId = $(event.currentTarget).attr('data-id');
        let deleteSubscriptionUrl = $('#subscriptionPlanUrl').val() + '/' +
            subscriptionId
        deleteItem(deleteSubscriptionUrl, '#subscriptionPlanTable',
            Lang.get('messages.subscription_plans.subscription_plan'))
    })

    listenChange('.is_default', function (event) {
        let subscriptionPlanId = $(event.currentTarget).data('id')
        livewire.emit('refreshDatatable');
        livewire.emit('resetPageTable');
        updateStatusToDefault(subscriptionPlanId)

    })

    function updateStatusToDefault(subscriptionPlanId) {
        $.ajax({
            url: route('make.plan.default', subscriptionPlanId),
            method: 'post',
            cache: false,
            success: function (result) {
                if (result.success) {
                    displaySuccessMessage(result.message)
                }
            },
        })
    }

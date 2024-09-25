document.addEventListener('turbo:load', loadSubsPaymentMsg);

function loadSubsPaymentMsg() {
    paymentMessage();
}

window.paymentMessage = function (data = null) {
    if (toastData !== null) {
    toastData = data != null ? data : toastData;
        setTimeout(function () {
            $.toast({
                heading: toastData.toastType,
                icon: toastData.toastType,
                bgColor: '#7603f3',
                textColor: '#ffffff',
                text: toastData.toastMessage,
                position: 'top-right',
                stack: false,
            });
        }, 1000);
    }
};

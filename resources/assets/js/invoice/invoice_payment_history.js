document.addEventListener('DOMContentLoaded', invoicePaymentHistory);

function invoicePaymentHistory() {
    // payment mail in click after view payment transitions 
    if (!$('#paymentHistory-tab').length) {
        return false;
    }
    setTimeout(function (){
        let activeTab = location.href;
        let tabParameter = activeTab.substring(activeTab.indexOf("?active") + 8);
        $('.nav-item button[data-bs-target="#' + tabParameter + '"]').click();
    },100);
}

function searchDataTable (tbl, selector) {
    const filterSearch = document.querySelector(selector)
    filterSearch.addEventListener('keyup', function (e) {
        tbl.search(e.target.value).draw()
    })
    filterSearch.addEventListener('search', function (e) {
        tbl.search(e.target.value).draw()
    })
}

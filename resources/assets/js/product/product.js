listenClick('.product-delete-btn', function (event) {
    let productId = $(event.currentTarget).attr('data-id');
    deleteItem(route('products.destroy', productId), 'productTable',
        Lang.get('messages.product.product'));
});


<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class ProductController extends AppBaseController
{
    /**
     * @var ProductRepository
     */
    public $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    public function index(): \Illuminate\View\View
    {
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create(): \Illuminate\View\View
    {
        $categories = $this->productRepository->getData();

        return view('products.create', compact('categories'));
    }

    public function store(CreateProductRequest $request): RedirectResponse
    {
        $input = $request->all();
        $this->productRepository->store($input);
        Flash::success(__('messages.flash.product_created'));

        return redirect()->route('products.index');
    }

    public function edit($productId): \Illuminate\View\View
    {
        $product = Product::whereId($productId)->whereTenantId(Auth::user()->tenant_id)->first();
        $categories = $this->productRepository->getData();
        $product->load('category');

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $input = $request->all();
        $this->productRepository->updateProduct($input, $product->id);
        Flash::success(__('messages.flash.product_updated'));

        return redirect()->route('products.index');
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }
        $invoiceModels = [
            InvoiceItem::class,
        ];
        $result = canDelete($invoiceModels, 'product_id', $product->id);
        if ($result) {
            return $this->sendError(__('messages.flash.product_cant_deleted'));
        }
        $product->delete();

        return $this->sendSuccess(__('messages.flash.product_deleted'));
    }

    public function show($productId): \Illuminate\View\View
    {
        $product = Product::whereId($productId)->whereTenantId(Auth::user()->tenant_id)->first();
        $product->load('category');

        return view('products.show', compact('product'));
    }
}

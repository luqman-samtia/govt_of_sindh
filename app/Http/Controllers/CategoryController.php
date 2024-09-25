<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends AppBaseController
{
    /** @var CategoryRepository */
    public $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\View\View
    {
        return view('category.index');
    }

    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $input = $request->all();
        $category = $this->categoryRepository->store($input);

        return $this->sendResponse($category, __('messages.flash.category_saved'));
    }

    public function edit(Category $category): JsonResponse
    {
        if ($category->tenant_id != Auth::user()->tenant_id) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }

        return $this->sendResponse($category, __('messages.flash.category_retrieved'));
    }

    public function update(UpdateCategoryRequest $request, $categoryId): JsonResponse
    {
        $input = $request->all();
        $this->categoryRepository->updateCategory($input, $categoryId);

        return $this->sendSuccess(__('messages.flash.category_updated'));
    }

    /**
     * @param  Category  $category
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::whereId($id)->whereTenantId(Auth::user()->tenant_id)->first();
        if (! $category) {
            return $this->sendError(__('Seems, you are not allowed to access this record.'));
        }
        $productModels = [
            Product::class,
        ];
        $result = canDelete($productModels, 'category_id', $category->id);
        if ($result) {
            return $this->sendError(__('messages.flash.category_cant_deleted'));
        }
        $category->delete();

        return $this->sendSuccess(__('messages.flash.category_deleted'));
    }
}

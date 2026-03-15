<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()->orderBy('name')->paginate(15);

        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $category = Category::query()->create($request->validated());

        app(ActivityLogService::class)->log($request->user(), 'category.created', $category);

        return redirect()->route('admin.categories.index')->with('status', __('Category created.'));
    }

    public function show(Category $category): View
    {
        $this->authorize('view', $category);

        return view('admin.categories.show', ['category' => $category]);
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        app(ActivityLogService::class)->log($request->user(), 'category.updated', $category);

        return redirect()->route('admin.categories.show', $category)->with('status', __('Category updated.'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        app(ActivityLogService::class)->log(request()->user(), 'category.deleted', $category);

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', __('Category deleted.'));
    }
}

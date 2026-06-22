<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Support\Audit;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $category = Category::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'slug' => Slug::unique('categories', $data['name']),
        ]);

        Audit::log('category.create', 'category', $category->id);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria criada.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        $category->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'slug' => Slug::unique('categories', $data['name'], $category->id),
        ]);

        Audit::log('category.update', 'category', $category->id);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria atualizada.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->posts()->exists()) {
            return back()->with('error', 'Não é possível excluir uma categoria com notícias.');
        }

        $category->delete();

        Audit::log('category.delete', 'category', $category->id);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria excluída.');
    }
}

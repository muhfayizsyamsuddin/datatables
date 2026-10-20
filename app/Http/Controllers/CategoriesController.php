<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CategoriesController extends Controller
{
    /**
     * Display the categories listing page with DataTables.
     */
    public function index(): View
    {
        return view('categories.index');
    }

    /**
     * Server-side DataTables JSON endpoint.
     *
     * Uses query builder (not Eloquent) so we can select explicit columns and
     * join products count without N+1. Supports search on name/description and
     * sorting on all displayed columns.
     */
    public function data(Request $request): JsonResponse
    {
        // withCount menambahkan subquery COUNT otomatis; jangan override dengan ->select()
        // karena ->select() akan menimpa subquery COUNT tersebut dan menghasilkan products_count = null.
        $query = Category::query()->withCount('products');

        $dataTable = DataTables::eloquent($query)
            ->addColumn('products_count', function (Category $category) {
                return $category->products_count ?? 0;
            })
            ->editColumn('created_at', function (Category $category) {
                return $category->created_at ? $category->created_at->format('Y-m-d H:i') : '';
            })
            ->addColumn('action', function (Category $category) {
                return $category->id;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('categories.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('description', function ($query, $keyword) {
                $query->where('categories.description', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action']);

        // Bypass pagination for export requests
        if ($request->input('length') == -1 || (int) $request->input('length') >= 2147483647 || $request->boolean('export')) {
            $dataTable->skipPaging();
        }

        return $dataTable->toJson();
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Get a single category for editing (JSON).
     */
    public function edit(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category.
     *
     * Prevented by DB RESTRICT FK if products still reference this category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $productCount = $category->products()->count();

        if ($productCount > 0) {
            return redirect()->route('categories.index')
                ->with(
                    'error',
                    "Kategori tidak dapat dihapus karena masih digunakan oleh {$productCount} produk."
                );
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}

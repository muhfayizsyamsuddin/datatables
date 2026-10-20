<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
{
    /**
     * Display the products listing page with DataTables.
     * Passes all categories for the dropdown in the add/edit modal.
     */
    public function index(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('categories'));
    }

    /**
     * Server-side DataTables JSON endpoint using Yajra DataTables package.
     *
     * Uses a raw query builder with LEFT JOIN to avoid column name collisions
     * between products.id and categories.id. Explicit column aliasing ensures
     * DataTables can sort/search on category name server-side.
     */
    public function data(Request $request): JsonResponse
    {
        $query = Product::query()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select([
                'products.id',
                'products.name',
                'products.category_id',
                'products.category',                // kolom teks lama (fallback)
                'categories.name as category_name', // nama kategori dari relasi
                'products.price',
                'products.stock',
                'products.created_at',
            ]);

        $dataTable = DataTables::eloquent($query)
            ->editColumn('price', function (Product $product) {
                return (float) $product->price;
            })
            ->editColumn('created_at', function (Product $product) {
                return $product->created_at ? $product->created_at->format('Y-m-d H:i') : '';
            })
            ->addColumn('action', function (Product $product) {
                return $product->id;
            })
            // Tampilkan category_name jika tersedia, fallback ke kolom teks lama
            ->editColumn('category_name', function (Product $product) {
                return $product->category_name ?? $product->category ?? '-';
            })
            // Server-side search & sort pada category_name
            ->filterColumn('category_name', function ($query, $keyword) {
                $query->where('categories.name', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action']);

        // Bypass pagination untuk export
        if ($request->input('length') == -1 || (int) $request->input('length') >= 2147483647 || $request->boolean('export')) {
            $dataTable->skipPaging();
        }

        return $dataTable->toJson();
    }

    /**
     * Store a newly created product.
     * Accepts category_id from dropdown; also writes category text for backward compat.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        // Sync the old `category` text column for backward compatibility
        $category = Category::findOrFail($validated['category_id']);
        $validated['category'] = $category->name;

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Get a single product for editing (JSON).
     * Includes category_id so the dropdown can be pre-selected.
     */
    public function edit(Product $product): JsonResponse
    {
        return response()->json($product->only([
            'id', 'name', 'category', 'category_id', 'price', 'stock',
        ]));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        // Sync the old `category` text column for backward compatibility
        $category = Category::findOrFail($validated['category_id']);
        $validated['category'] = $category->name;

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}

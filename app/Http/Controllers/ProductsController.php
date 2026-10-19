<?php

namespace App\Http\Controllers;

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
     */
    public function index(): View
    {
        return view('products.index');
    }

    /**
     * Server-side DataTables JSON endpoint using Yajra DataTables package.
     *
     * Processes pagination, global search, column sorting, and records count
     * directly at database level via Eloquent query builder.
     */
    public function data(Request $request): JsonResponse
    {
        $dataTable = DataTables::eloquent(Product::query())
            ->editColumn('price', function (Product $product) {
                return (float) $product->price;
            })
            ->editColumn('created_at', function (Product $product) {
                return $product->created_at ? $product->created_at->format('Y-m-d H:i') : '';
            })
            ->addColumn('action', function (Product $product) {
                return $product->id;
            })
            ->rawColumns(['action']);

        // When export is triggered or length is -1 / max int, bypass pagination to return all matching records
        if ($request->input('length') == -1 || (int) $request->input('length') >= 2147483647 || $request->boolean('export')) {
            $dataTable->skipPaging();
        }

        return $dataTable->toJson();
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Get a single product for editing (JSON).
     */
    public function edit(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

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

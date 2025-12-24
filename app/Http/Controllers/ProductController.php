<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Fetch products from FakeStore API and store them in the database.
     */
    public function fetchFromApi(): RedirectResponse
    {
        try {
            $response = Http::get('https://fakestoreapi.com/products');

            if (! $response->successful()) {
                return redirect()->back()->with('error', 'Failed to fetch products from API.');
            }

            $products = $response->json();

            $fetchedCount = 0;
            $skippedCount = 0;

            foreach ($products as $productData) {
                $existingProduct = Product::where('api_product_id', $productData['id'])->first();

                if (! $existingProduct) {
                    Product::create([
                        'api_product_id' => $productData['id'],
                        'title' => $productData['title'],
                        'price' => $productData['price'],
                        'description' => $productData['description'],
                        'category' => $productData['category'],
                        'image' => $productData['image'],
                        'rating_rate' => $productData['rating']['rate'],
                        'rating_count' => $productData['rating']['count'],
                    ]);
                    $fetchedCount++;
                } else {
                    $skippedCount++;
                }
            }

            return redirect()->route('products.index')
                ->with('success', "Successfully fetched {$fetchedCount} products from API. {$skippedCount} duplicates were skipped.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching products: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|url',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}

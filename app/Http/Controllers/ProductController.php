<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $apiUrl = 'http://127.0.0.1:3000/api/product';

    protected function extractItems($response)
    {
        $json = $response->json();
        
        if (isset($json['data']['items']) && is_array($json['data']['items'])) {
            return $json['data']['items'];
        }
        
        if (is_array($json) && !isset($json['success'])) {
            return $json;
        }
        
        return [];
    }

    protected function extractItem($response)
    {
        $json = $response->json();
        
        if (isset($json['data']) && is_array($json['data']) && !isset($json['data']['items'])) {
            return $json['data'];
        }
        
        if (isset($json['data']['items']) && is_array($json['data']['items'])) {
            return $json['data']['items'][0] ?? null;
        }
        
        return $json;
    }

    protected function mapProduct($product)
    {
        if (!$product) return null;
        
        return [
            'id'          => $product['id'] ?? $product['_id'] ?? null,
            'name'        => $product['name'] ?? 'Unknown',
            'stock'       => (int)($product['quantity'] ?? 0),
            'price'       => (float)($product['price_out'] ?? $product['price_in'] ?? 0),
            'price_in'    => (float)($product['price_in'] ?? 0),
            'price_out'   => (float)($product['price_out'] ?? 0),
            'description' => $product['description'] ?? '',
            'image'       => $product['image'] ?? null,
            'created_at'  => $product['createdAt'] ?? $product['created_at'] ?? null,
            'updated_at'  => $product['updatedAt'] ?? $product['updated_at'] ?? null,
        ];
    }

    // Dashboard
   public function dashboard()
{
    try {
        $response = Http::get($this->apiUrl);
        $products = [];
        
        if ($response->successful()) {
            $items = $this->extractItems($response);
            $products = array_filter(array_map([$this, 'mapProduct'], $items));
            
            // 🔧 REVERSE ARRAY - New products at bottom
            $products = array_reverse($products);
        }
        
        $totalProducts = count($products);
        $totalValue = array_sum(array_column($products, 'price_out'));
        $lowStock = count(array_filter($products, fn($p) => $p['stock'] > 0 && $p['stock'] <= 10));
        $outOfStock = count(array_filter($products, fn($p) => $p['stock'] == 0));
        
        // Get first 5 products (oldest) for recent products section
        $recentProducts = array_slice($products, 0, 5);
        
        return view('dashboard', compact(
            'totalProducts',
            'totalValue',
            'lowStock',
            'outOfStock',
            'recentProducts'
        ));
        
    } catch (\Exception $e) {
        return view('dashboard', [
            'totalProducts' => 0,
            'totalValue' => 0,
            'lowStock' => 0,
            'outOfStock' => 0,
            'recentProducts' => []
        ]);
    }
}
    // List all products
    public function index()
{
    $products = [];
    
    try {
        $response = Http::timeout(10)->get($this->apiUrl);
        
        if ($response->successful()) {
            $items = $this->extractItems($response);
            $products = array_filter(array_map([$this, 'mapProduct'], $items));
            
            // 🔧 REVERSE ARRAY - New products at bottom
            $products = array_reverse($products);
            
        } else {
            Log::error('API Error [' . $response->status() . ']: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('API Connection Error: ' . $e->getMessage());
    }
    
    return view('products.index', compact('products'));
}

    // Show create form
    public function create()
    {
        return view('products.create');
    }

    // Store new product
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'price_in'    => 'required|numeric|min:0',
                'price_out'   => 'required|numeric|min:0',
                'stock'       => 'required|integer|min:0',
                'description' => 'nullable|string',
            ]);

            $apiData = [
                'name'        => $validated['name'],
                'quantity'    => (int)$validated['stock'],
                'price_in'    => (float)$validated['price_in'],
                'price_out'   => (float)$validated['price_out'],
                'description' => $validated['description'] ?? '',
            ];

            $response = Http::post($this->apiUrl, $apiData);
            
            if ($response->successful()) {
                return redirect()->route('products.index')
                    ->with('success', 'Product created successfully!');
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Connection error: ' . $e->getMessage());
        }
    }

    // Show edit form
    public function edit($id)
    {
        try {
            $response = Http::get("{$this->apiUrl}/{$id}");
            
            if (!$response->successful()) {
                return redirect()->route('products.index')
                    ->with('error', 'Product not found');
            }
            
            $product = $this->extractItem($response);
            $product = $this->mapProduct($product);
            
            if (!$product || !$product['id']) {
                return redirect()->route('products.index')
                    ->with('error', 'Product not found');
            }
            
            return view('products.edit', compact('product'));
            
        } catch (\Exception $e) {
            Log::error('Edit Error: ' . $e->getMessage());
            return redirect()->route('products.index')
                ->with('error', 'Failed to load product');
        }
    }

    // Update product
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'price_in'    => 'required|numeric|min:0',
                'price_out'   => 'required|numeric|min:0',
                'stock'       => 'required|integer|min:0',
                'description' => 'nullable|string',
            ]);

            $apiData = [
                'name'        => $validated['name'],
                'quantity'    => (int)$validated['stock'],
                'price_in'    => (float)$validated['price_in'],
                'price_out'   => (float)$validated['price_out'],
                'description' => $validated['description'] ?? '',
            ];

            $response = Http::put("{$this->apiUrl}/{$id}", $apiData);
            
            if ($response->successful()) {
                return redirect()->route('products.index')
                    ->with('success', 'Product updated successfully!');
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product');
                
        } catch (\Exception $e) {
            Log::error('Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Connection error: ' . $e->getMessage());
        }
    }

    // Show delete confirmation page
    public function showDelete($id)
    {
        try {
            $response = Http::get("{$this->apiUrl}/{$id}");
            
            if (!$response->successful()) {
                return redirect()->route('products.index')
                    ->with('error', 'Product not found');
            }
            
            $product = $this->extractItem($response);
            $product = $this->mapProduct($product);
            
            return view('products.delete', compact('product'));
            
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Failed to load product');
        }
    }

    // Delete product
    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl}/{$id}");
            
            if ($response->successful()) {
                return redirect()->route('products.index')
                    ->with('success', 'Product deleted successfully!');
            }
            
            return redirect()->back()
                ->with('error', 'Failed to delete product');
                
        } catch (\Exception $e) {
            Log::error('Delete Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Connection error: ' . $e->getMessage());
        }
    }
}
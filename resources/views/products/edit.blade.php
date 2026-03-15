@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Edit #{{ $product['id'] }}</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Update Product Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product['id']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name', $product['name']) }}"
                               required 
                               placeholder="e.g., Coca Cola">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cost Price ($) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('price_in') is-invalid @enderror" 
                                   name="price_in" 
                                   value="{{ old('price_in', $product['price_in']) }}"
                                   step="0.01" 
                                   min="0" 
                                   required 
                                   placeholder="0.00">
                            @error('price_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Selling Price ($) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('price_out') is-invalid @enderror" 
                                   name="price_out" 
                                   value="{{ old('price_out', $product['price_out']) }}"
                                   step="0.01" 
                                   min="0" 
                                   required 
                                   placeholder="0.00">
                            @error('price_out')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               name="stock" 
                               value="{{ old('stock', $product['stock']) }}"
                               min="0" 
                               required 
                               placeholder="0">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
    <label class="form-label">Image URL <span class="text-muted">(Optional)</span></label>
    <input type="url" 
           class="form-control @error('image') is-invalid @enderror" 
           name="image" 
           value="{{ old('image', $product['image']) }}"
           placeholder="https://example.com/product-image.jpg">
    <small class="text-muted">Enter a direct link to the product image</small>
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea class="form-control @error('description') is-invalid @enderror" 
              name="description" 
              rows="4" 
              placeholder="Product description...">{{ old('description', $product['description']) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Update Product
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
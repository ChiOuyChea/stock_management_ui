@extends('layouts.app')

@section('title', 'Delete Product')
@section('page-title', 'Delete Product')

@section('content')

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">Delete</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete</h5>
                </div>
                <div class="card-body text-center py-5">
                    {{-- <div class="mb-4" style="font-size: 4rem;">⚠️</div> --}}

                    <h4 class="mb-3">Are you sure you want to delete this product?</h4>

                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-2">{{ $product['name'] }}</h5>
                            <p class="mb-1"><strong>Cost Price:</strong> ${{ number_format($product['price_in'], 2) }}</p>
                            <p class="mb-1"><strong>Selling Price:</strong> ${{ number_format($product['price_out'], 2) }}
                            </p>
                            <p class="mb-0"><strong>Stock:</strong> {{ $product['stock'] }} units</p>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>

                    <div class="d-flex gap-3 justify-content-center">
                        <form action="{{ route('products.destroy', $product['id']) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-trash me-2"></i>Yes, Delete Product
                            </button>
                        </form>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-x-lg me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">📦</div>
        <div class="stat-info">
            <h6>Total Products</h6>
            <div class="value">{{ $totalProducts ?? 0 }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">💰</div>
        <div class="stat-info">
            <h6>Total Value</h6>
            <div class="value">${{ number_format($totalValue ?? 0, 2) }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">⚠️</div>
        <div class="stat-info">
            <h6>Low Stock</h6>
            <div class="value">{{ $lowStock ?? 0 }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">❌</div>
        <div class="stat-info">
            <h6>Out of Stock</h6>
            <div class="value">{{ $outOfStock ?? 0 }}</div>
        </div>
    </div>
</div>

<!-- Recent Products -->
<div class="card">
    <div class="card-header">
        <h5>Recent Products</h5>
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentProducts ?? [] as $product)
                    <tr>
                        <td><strong>{{ $product['name'] }}</strong></td>
                        <td>{{ $product['stock'] }}</td>
                        <td>${{ number_format($product['price'], 2) }}</td>
                        <td>
                            @if($product['stock'] > 10)
                                <span class="badge badge-success">In Stock</span>
                            @elseif($product['stock'] > 0)
                                <span class="badge badge-warning">Low Stock</span>
                            @else
                                <span class="badge badge-danger">Out of Stock</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No products available
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
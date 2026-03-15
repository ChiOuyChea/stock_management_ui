@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Stock Management')

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Action Bar -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">All Products</h2>
        <p class="text-muted mb-0">Manage your inventory, pricing, and stock levels</p>
    </div>
    {{-- <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Product
    </a> --}}
</div>

<!-- Stats Cards -->
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-icon primary">📦</div>
        <div class="stat-info">
            <h6>Total Products</h6>
            <div class="value">{{ count($products) }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">💰</div>
        <div class="stat-info">
            <h6>Total Value</h6>
            <div class="value">${{ number_format(array_sum(array_column($products, 'price_out')), 2) }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">⚠️</div>
        <div class="stat-info">
            <h6>Low Stock</h6>
            <div class="value">{{ count(array_filter($products, fn($p) => $p['stock'] > 0 && $p['stock'] <= 10)) }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">❌</div>
        <div class="stat-info">
            <h6>Out of Stock</h6>
            <div class="value">{{ count(array_filter($products, fn($p) => $p['stock'] == 0)) }}</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" 
                           class="form-control border-start-0" 
                           id="searchInput" 
                           placeholder="Search products by name...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="instock">In Stock</option>
                    <option value="lowstock">Low Stock (≤10)</option>
                    <option value="outstock">Out of Stock</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-outline-secondary" onclick="resetFilters()">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Product List</h5>
        <span class="text-muted small">{{ count($products) }} items found</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="productsTable">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Product Name</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody id="productsBody">
                    @forelse($products as $index => $product)
                    <tr data-name="{{ strtolower($product['name']) }}" 
                        data-stock="{{ $product['stock'] }}">
                        
                        <td class="ps-4">
                            <span class="text-muted">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-box-seam text-primary"></i>
                                <div>
                                    <strong class="d-block">{{ $product['name'] }}</strong>
                                    @if(!empty($product['description']))
                                    <small class="text-muted">
                                        {{ Str::limit($product['description'], 40) }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span class="text-muted">${{ number_format($product['price_in'], 2) }}</span>
                        </td>
                        
                        <td>
                            <strong class="text-success">${{ number_format($product['price_out'], 2) }}</strong>
                        </td>
                        
                        <td>
                            <span class="{{ $product['stock'] <= 5 ? 'text-danger fw-bold' : 'text-dark' }}">
                                {{ $product['stock'] }}
                            </span>
                        </td>
                        
                        <td>
                            @if($product['stock'] > 10)
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle me-1"></i>In Stock
                                </span>
                            @elseif($product['stock'] > 0)
                                <span class="badge badge-warning">
                                    <i class="bi bi-exclamation-circle me-1"></i>Low Stock
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="bi bi-x-circle me-1"></i>Out of Stock
                                </span>
                            @endif
                        </td>
                        
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('products.edit', $product['id']) }}" 
                                   class="btn btn-sm btn-success" 
                                   title="Edit Product">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="{{ route('products.show-delete', $product['id']) }}" 
                                   class="btn btn-sm btn-danger" 
                                   title="Delete Product">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="mb-3" style="font-size: 4rem;">📦</div>
                            <h5 class="text-muted mb-2">No products found</h5>
                            <p class="text-muted mb-4">Get started by adding your first product</p>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Add Your First Product
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="d-flex justify-content-between align-items-center mb-4">
    {{-- <div>
        <h2 class="mb-1">All Products</h2>
        <p class="text-muted mb-0">Manage your inventory, pricing, and stock levels</p>
    </div> --}}
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Product
    </a>
</div>
@endsection

@push('scripts')
<script>
// ===== Search and Filter Functionality =====
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const productsBody = document.getElementById('productsBody');

if (searchInput) {
    searchInput.addEventListener('input', filterProducts);
}

if (statusFilter) {
    statusFilter.addEventListener('change', filterProducts);
}

function filterProducts() {
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const statusValue = statusFilter ? statusFilter.value : '';
    
    const rows = productsBody ? productsBody.querySelectorAll('tr[data-name]') : [];
    
    rows.forEach(row => {
        const name = row.dataset.name || '';
        const stock = parseInt(row.dataset.stock) || 0;
        
        // Search match
        let matchesSearch = name.includes(searchTerm);
        
        // Status match
        let matchesStatus = true;
        if (statusValue === 'instock') {
            matchesStatus = stock > 10;
        } else if (statusValue === 'lowstock') {
            matchesStatus = stock > 0 && stock <= 10;
        } else if (statusValue === 'outstock') {
            matchesStatus = stock === 0;
        }
        
        // Show/hide row
        row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });
}

function resetFilters() {
    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';
    filterProducts();
}

// ===== Auto-hide alerts after 5 seconds =====
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush
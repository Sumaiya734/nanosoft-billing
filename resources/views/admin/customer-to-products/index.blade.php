@extends('layouts.admin')

@section('title', 'Customer Products')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="page-title"><i class="fas fa-user-tag me-2"></i>Customer to Products</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.customer-to-products.assign') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Assign Products
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <i class="fas fa-users stats-icon"></i>
                <div class="stats-number">{{ $totalCustomers }}</div>
                <div class="stats-label">Total Customers</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <i class="fas fa-cube stats-icon"></i>
                <div class="stats-number">{{ $activeProducts }}</div>
                <div class="stats-label">Active Products</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <i class="fas fa-taka-sign stats-icon"></i>
                <div class="stats-number">৳ {{ number_format($monthlyRevenue, 2) }}</div>
                <div class="stats-label">Monthly Revenue</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <i class="fas fa-sync stats-icon"></i>
                <div class="stats-number">{{ $renewalsDue }}</div>
                <div class="stats-label">Renewals Due</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.customer-to-products.index') }}" method="GET" id="searchForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Customers</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   placeholder="Search by name, email, phone, or customer ID..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Product Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="product_type" class="form-label">Product Type</label>
                        <select class="form-select" id="product_type" name="product_type">
                            <option value="">All Types</option>
                            <option value="regular" {{ request('product_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="special" {{ request('product_type') == 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                        </div>
                    </div>
                </div>
                @if(request()->hasAny(['search', 'status', 'product_type']))
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center flex-wrap">
                            
                            @if(request('search'))
                                <span class="badge bg-primary me-2 mb-1">
                                    Search: "{{ request('search') }}"
                                    <a href="javascript:void(0)" onclick="removeFilter('search')" class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('status'))
                                <span class="badge bg-info me-2 mb-1">
                                    Status: {{ ucfirst(request('status')) }}
                                    <a href="javascript:void(0)" onclick="removeFilter('status')" class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('product_type'))
                                <span class="badge bg-warning me-2 mb-1">
                                    Type: {{ ucfirst(request('product_type')) }}
                                    <a href="javascript:void(0)" onclick="removeFilter('product_type')" class="text-white ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Customer Products Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Customer Info</th>
                        <th>Product List</th>
                        <th>Product Price</th>
                        <th>Assign Date</th>
                        <th>Billing Months</th>
                        <th>Total Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        @if($customer->customerProducts->count() > 0)
                            @foreach($customer->customerProducts as $index => $cp)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ $customer->customerProducts->count() }}">
                                            <div class="customer-name">{{ $customer->name }}</div>
                                            <div class="customer-email">{{ $customer->email ?? 'No email' }}</div>
                                            <small class="text-muted">ID: {{ $customer->customer_id }}</small>
                                            <div class="mt-2">
                                                <span class="badge bg-{{ $customer->is_active ? 'success' : 'secondary' }}">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                                    {{ $customer->is_active ? 'Active Customer' : 'Inactive Customer' }}
                                                </span>
                                            </div>
                                        </td>
                                    @endif
                                    
                                    <td class="product-cell">
                                        <div class="product-badge {{ optional($cp->product)->product_type === 'regular' ? 'regular-product' : 'special-product' }}">
                                            {{ optional($cp->product)->name ?? 'Unknown product' }}
                                            @if(optional($cp->product)->product_type === 'regular')
                                                <small class="d-block text-muted">Main product</small>
                                            @else
                                                <small class="d-block text-muted">Add-on</small>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="price-cell">
<<<<<<< HEAD
                                        <div><span class="currency">৳</span> {{ number_format($cp->product_price ?? 0, 2) }}</div>
=======
                                        <div><span class="currency">৳</span> {{ number_format(optional($cp->product)->monthly_price ?? 0, 2) }}</div>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                    </td>
                                    
                                    <td class="text-center">
                                        <div>{{ \Carbon\Carbon::parse($cp->assign_date)->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($cp->assign_date)->diffForHumans() }}</small>
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="billing-months">{{ $cp->billing_cycle_months }} Month{{ $cp->billing_cycle_months > 1 ? 's' : '' }}</div>
                                    </td>
                                    
                                    <!-- FIXED: Simplified total amount display using model attribute -->
                                    <td class="price-cell">
                                        <div class="total-price">
<<<<<<< HEAD
                                            <strong class="text-dark">৳ {{ number_format($cp->total_amount, 2) }}</strong>
                                            <div class="text-muted small">
                                                (৳{{ number_format($cp->product_price ?? 0, 2) }} × {{ $cp->billing_cycle_months }})
                                            </div>
                                        </div>
                                    </td>

                                    <!-- FIXED: Due Date - shows saved value or default -->
                                    <td class="text-center">
                                        <div class="due-day">
                                            @if($cp->due_date)
                                                {{ \Carbon\Carbon::parse($cp->due_date)->format('M d, Y') }}
                                            @else
                                                @php
                                                    $assignDate = \Carbon\Carbon::parse($cp->assign_date);
                                                    $billingCycleMonths = $cp->billing_cycle_months ?? 1;
                                                    $defaultDay = $assignDate->day > 28 ? 28 : $assignDate->day;
                                                    $displayDate = $assignDate->copy()->addMonths($billingCycleMonths)->day($defaultDay);
                                                @endphp
                                                <span class="text-muted">{{ $displayDate->format('M d, Y') }}*</span>
                                            @endif
=======
                                            <span class="currency">৳</span> 
                                            {{ number_format((optional($cp->product)->monthly_price ?? 0) * $cp->billing_cycle_months, 2) }}
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                        </div>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="text-center">
                                        @php
                                            $statusClass = [
                                                'active' => 'bg-success',
                                                'pending' => 'bg-warning',
                                                'expired' => 'bg-danger'
                                            ][$cp->status] ?? 'bg-secondary';
                                            
                                            $statusIcons = [
                                                'active' => 'fa-check-circle',
                                                'pending' => 'fa-clock',
                                                'expired' => 'fa-times-circle'
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusClass }} status-badge">
                                            <i class="fas {{ $statusIcons[$cp->status] ?? 'fa-question-circle' }} me-1"></i>
                                            {{ ucfirst($cp->status) }}
                                        </span>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="btn-group d-flex justify-content-center gap-1">
                                            @if($cp->cp_id)
<<<<<<< HEAD
=======
                                                <!-- Edit Button -->
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                                <a href="{{ route('admin.customer-to-products.edit', $cp->cp_id) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit product">
                                                   <i class="fas fa-edit"></i>
                                                </a>

                                                @php
                                                    $isActive = $cp->status === 'active';
<<<<<<< HEAD
=======
                                                    $newStatus = $isActive ? 'expired' : 'active';
                                                    $confirmText = $isActive
                                                        ? 'Are you sure you want to pause this product?'
                                                        : 'Are you sure you want to activate this product?';
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                                    $buttonIcon = $isActive ? 'fa-pause' : 'fa-play';
                                                    $buttonTitle = $isActive ? 'Pause product' : 'Activate product';
                                                @endphp

<<<<<<< HEAD
=======
                                                <!-- Toggle Status Button -->
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                                <form action="{{ route('admin.customer-to-products.toggle-status', $cp->cp_id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="{{ $buttonTitle }}">
                                                        <i class="fas {{ $buttonIcon }}"></i>
                                                    </button>
                                                </form>

<<<<<<< HEAD
                                                <form action="{{ route('admin.customer-to-products.destroy', $cp->cp_id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to remove this product?');">
=======
                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.customer-to-products.destroy', $cp->cp_id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to remove this product? This cannot be undone.');">
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete product">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">No actions</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5>No Customer Products Found</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'status', 'product_type']))
                                        No products found matching your search criteria.
                                    @else
                                        No products have been assigned to customers yet.
                                    @endif
                                </p>
                                @if(request()->hasAny(['search', 'status', 'product_type']))
                                    <a href="{{ route('admin.customer-to-products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Clear Search
                                    </a>
                                @else
                                    <a href="{{ route('admin.customer-to-products.assign') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Assign First Product
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                @if(request()->hasAny(['search', 'status', 'product_type']))
                    <span class="badge bg-info ms-2">Filtered Results</span>
                @endif
            </div>
            <nav>
                {{ $customers->withQueryString()->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    @endif
    
    <!-- Legend for default due dates -->
    <div class="mt-3">
        <small class="text-muted">
            <sup>*</sup> Default due date calculated from assign date
        </small>
    </div>
</div>

<style>
    .page-title {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #3498db;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .table-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .table {
        border: 2px solid #dee2e6;
        margin-bottom: 0;
    }
    .table th {
        border: 2px solid #dee2e6;
        font-weight: 600;
        padding: 15px;
        text-align: center;
        vertical-align: middle;
        background: #2c3e50;
        color: white;
    }
    .table td {
        padding: 15px;
        vertical-align: middle;
        border: 2px solid #dee2e6;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .product-badge {
        border-radius: 20px;
        padding: 8px 15px;
        margin: 2px;
        display: inline-block;
        font-size: 0.85rem;
        border: 1px solid;
        text-align: center;
        min-width: 120px;
    }
    .regular-product {
        background-color: #e3f2fd;
        color: #1976d2;
        border-color: #bbdefb;
    }
    .special-product {
        background-color: #fff3e0;
        color: #f57c00;
        border-color: #ffe0b2;
    }
    .customer-name {
        font-weight: 600;
        color: #2c3e50;
    }
    .customer-email {
        font-size: 0.85rem;
        color: #7f8c8d;
    }
<<<<<<< HEAD
    .due-day {
        font-weight: 600;
        color: #27ae60;
        font-size: 1rem;
    }
    .due-day sup {
        font-size: 0.65rem;
        top: -0.3em;
    }
=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
</style>

<script>
    function removeFilter(filterName) {
        const url = new URL(window.location);
        url.searchParams.delete(filterName);
        window.location = url.toString();
    }
    
    function clearSearch() {
        document.getElementById('search').value = '';
        document.getElementById('searchForm').submit();
    }
    
<<<<<<< HEAD
    // Auto-submit on filter change
    document.addEventListener('DOMContentLoaded', function() {
        ['status', 'product_type'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', () => document.getElementById('searchForm').submit());
=======
    // Auto-submit form when filters change
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
        
        document.getElementById('product_type').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
        });
    });
</script>
@endsection
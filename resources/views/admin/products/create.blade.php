@extends('layouts.admin')

@section('title', 'Create New Product - Admin Dashboard')

@section('content')
<div class="container-fluid p-4">
    <!-- Toast container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="toastContainer"></div>
    </div>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 page-title">
                <i class="fas fa-plus me-2 text-primary"></i>Create New Product
            </h2>
            <p class="text-muted mb-0">Add a new internet product to your offerings</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Create Product Form Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cube me-2"></i>Product Details
                    </h5>
                </div>
                <div class="card-body">
                    <form id="createProductForm" method="POST" action="{{ route('admin.products.store') }}">
                        @csrf
                        
                        <div id="createErrors" class="alert alert-danger d-none"></div>

                        <!-- Product Name -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Name *</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control form-control-lg" 
                                   placeholder="e.g., Basic Plan, Premium Speed, Business Product" 
                                   required
                                   autofocus>
                            <div class="form-text">
                                Choose a descriptive name for your product.
                            </div>
                        </div>

                        <!-- Product Type -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Type *</label>
                            <select name="product_type_id" class="form-control form-control-lg" required>
                                <option value="">Select Product Type</option>
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                Select the category for this product.
                            </div>
                        </div>

                        <!-- Monthly Price -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Monthly Price (৳) *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">৳</span>
                                <input type="number" 
                                       name="monthly_price" 
                                       class="form-control" 
                                       placeholder="0.00" 
                                       step="0.01" 
                                       min="0" 
                                       required>
                            </div>
                            <div class="form-text">
                                Enter the monthly subscription price in Bangladeshi Taka.
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Description *</label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Describe the product features, speed, benefits, and any limitations..."
                                      required></textarea>
                            <div class="form-text">
                                Provide detailed information about what this product includes.
                            </div>
                        </div>

                        <!-- Features (Optional) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Key Features (Optional)</label>
                            <div class="features-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control feature-input" placeholder="e.g., 100 Mbps Speed">
                                    <button type="button" class="btn btn-outline-primary add-feature-btn">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-text">
                                Add key features that make this product attractive to customers.
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Tip:</strong> Make sure to provide clear and accurate information about your product to help customers make informed decisions.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="createProductBtn">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                <i class="fas fa-plus me-2"></i>Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Preview -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>Product Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div id="productPreview" class="text-muted text-center py-4">
                        <i class="fas fa-cube fa-3x mb-3"></i>
                        <p>Your product preview will appear here as you fill out the form.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-control-lg {
        padding: 12px 16px;
        font-size: 1.1rem;
    }
    
    .btn-lg {
        padding: 12px 24px;
        font-size: 1.1rem;
    }

    .feature-tag {
        display: inline-block;
        background: #e9ecef;
        padding: 4px 12px;
        margin: 2px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .feature-tag .remove-feature {
        cursor: pointer;
        margin-left: 8px;
        color: #6c757d;
    }

    .feature-tag .remove-feature:hover {
        color: #dc3545;
    }

    .product-preview-item {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }

    .product-preview-item h6 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CSRF token with error handling
    });
</script>
@endsection

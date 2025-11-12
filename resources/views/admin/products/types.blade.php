@extends('layouts.admin')

@section('title', 'Product Types - Admin Dashboard')

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
                <i class="fas fa-tags me-2 text-primary"></i>Product Types
            </h2>
            <p class="text-muted mt-2">Manage product types that categorize your products.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- Create Product Type Form -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Create New Product Type
                    </h5>
                </div>
                <div class="card-body">
                    <form id="createProductTypeForm">
                        @csrf
                        
                        <div id="createErrors" class="alert alert-danger d-none"></div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Type Name *</label>
                            <input type="text" 
                                   name="name" 
                                   id="productTypeName"
                                   class="form-control form-control-lg" 
                                   placeholder="Enter product type (e.g., premium, business, basic)" 
                                   required
                                   autofocus>
                            <div class="form-text">
                                This will be used as the product type when creating new products. Must be unique.
                            </div>
                            <div id="typeDuplicateWarning" class="text-warning small mt-1 d-none">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <span id="typeDuplicateMessage"></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="descriptions" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Enter a description for this product type (optional)"></textarea>
                            <div class="form-text">
                                Provide a brief description of this product type.
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Newly created product types will appear at the top of the list.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="createProductTypeBtn">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                <i class="fas fa-plus me-2"></i>Add Product Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Existing Product Types -->
            @if($productTypes->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Existing Product Types
                        <span class="badge bg-primary ms-2">{{ $productTypes->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="productTypesList">
                        @foreach($productTypes as $type)
                        <div class="col-md-12 mb-3">
                            <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                <div class="d-flex align-items-center">
                                    <div class="product-type-icon me-3 
                                        {{ $type->name === 'regular' ? 'bg-primary' : 
                                           ($type->name === 'special' ? 'bg-warning' : 
                                           ($type->created_at->diffInMinutes(now()) < 10 ? 'bg-info' : 'bg-success')) }}">
                                        <i class="fas fa-tag"></i>
                                        @if($type->created_at->diffInMinutes(now()) < 10)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                            NEW
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-capitalize">
                                            {{ $type->name }}
                                            @if($type->created_at->diffInMinutes(now()) < 10)
                                            <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">NEW</span>
                                            @endif
                                        </h6>
                                        @if($type->descriptions)
                                            <small class="text-muted d-block">{{ $type->descriptions }}</small>
                                        @endif
                                        <small class="text-muted">
                                            {{ $productCounts[$type->name] ?? 0 }} products
                                            • Created: {{ $type->created_at->format('M j, Y g:i A') }}
                                        </small>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-danger delete-product-type" 
                                        data-id="{{ $type->id }}" 
                                        data-name="{{ $type->name }}"
                                        title="Delete {{ $type->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Product Types Found</h5>
                    <p class="text-muted">Start by adding your first product type above.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .product-type-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        position: relative;
    }
    
    .form-control-lg {
        padding: 12px 16px;
        font-size: 1.1rem;
    }
    
    .btn-lg {
        padding: 12px 24px;
        font-size: 1.1rem;
    }

    .border-rounded {
        border-radius: 12px;
    }

    .is-duplicate {
        border-color: #ffc107 !important;
        background-color: #fffbf0;
    }

    .is-conflict {
        border-color: #fd7e14 !important;
        background-color: #fff4e6;
    }

    .new-type-highlight {
        border-left: 4px solid #0dcaf0;
        background-color: #f8f9fa;
    }

    .badge-new {
        font-size: 0.6rem;
        padding: 2px 6px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Toast helper
        function showToast(message, type = 'success') {
            const typeMap = {
                'success': 'success',
                'error': 'danger',
                'danger': 'danger',
                'warning': 'warning',
                'info': 'info'
            };
            
            const bootstrapType = typeMap[type] || 'success';
            const toastId = 'toast-' + Date.now();
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <div id="${toastId}" class="toast align-items-center text-bg-${bootstrapType} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${type === 'success' ? '<i class="fas fa-check-circle me-2"></i>' : ''}
                            ${type === 'error' || type === 'danger' ? '<i class="fas fa-exclamation-circle me-2"></i>' : ''}
                            ${type === 'warning' ? '<i class="fas fa-exclamation-triangle me-2"></i>' : ''}
                            ${type === 'info' ? '<i class="fas fa-info-circle me-2"></i>' : ''}
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            document.getElementById('toastContainer').appendChild(wrapper.firstElementChild);
            const toastEl = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        }

        // Show validation errors
        function showValidationErrors(containerEl, errors) {
            if (!containerEl) return;
            containerEl.classList.remove('d-none');
            
            let errorHtml = '';
            if (typeof errors === 'string') {
                errorHtml = errors;
            } else if (errors.message) {
                errorHtml = errors.message;
            } else if (errors.errors) {
                errorHtml = Object.values(errors.errors).flat().map(e => `<div>• ${e}</div>`).join('');
            } else {
                errorHtml = Object.values(errors).flat().map(e => `<div>• ${e}</div>`).join('');
            }
            
            containerEl.innerHTML = errorHtml;
        }

        // Clear validation errors
        function clearValidationErrors() {
            const errorsEl = document.getElementById('createErrors');
            if (errorsEl) {
                errorsEl.classList.add('d-none');
                errorsEl.innerHTML = '';
            }
        }

        // Real-time duplicate checking for product types
        let typeDuplicateCheckTimeout;
        
        document.getElementById('productTypeName').addEventListener('input', function() {
            const typeName = this.value.trim();
            
            if (typeName.length < 2) {
                hideTypeDuplicateWarning();
                return;
            }
            
            clearTimeout(typeDuplicateCheckTimeout);
            typeDuplicateCheckTimeout = setTimeout(async () => {
                try {
                    // Check against existing types
                    const existingTypes = @json($productTypes->pluck('name'));
                    const isDuplicate = existingTypes.some(existingType => 
                        existingType.toLowerCase() === typeName.toLowerCase()
                    );
                    
                    if (isDuplicate) {
                        showTypeDuplicateWarning(`A product type with similar name already exists.`);
                    } else {
                        hideTypeDuplicateWarning();
                    }
                } catch (error) {
                    console.error('Type duplicate check failed:', error);
                }
            }, 500);
        });

        function showTypeDuplicateWarning(message) {
            const warning = document.getElementById('typeDuplicateWarning');
            const messageEl = document.getElementById('typeDuplicateMessage');
            const input = document.getElementById('productTypeName');
            
            warning.classList.remove('d-none');
            messageEl.textContent = message;
            input.classList.add('is-conflict');
        }

        function hideTypeDuplicateWarning() {
            const warning = document.getElementById('typeDuplicateWarning');
            const input = document.getElementById('productTypeName');
            
            warning.classList.add('d-none');
            input.classList.remove('is-conflict');
        }

        // CREATE: Product Type Form Submission
        const createForm = document.getElementById('createProductTypeForm');
        if (createForm) {
            createForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const form = e.target;
                const btn = document.getElementById('createProductTypeBtn');
                const spinner = btn?.querySelector('.spinner-border');
                const errorContainer = document.getElementById('createErrors');
                
                // Disable button and show spinner
                if (btn) btn.disabled = true;
                if (spinner) spinner.classList.remove('d-none');
                if (errorContainer) errorContainer.classList.add('d-none');

                const formData = new FormData(form);
                
                try {
                    const response = await fetch('{{ route("admin.products.add-type") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const jsonResponse = await response.json();

                    if (!response.ok) {
                        showValidationErrors(errorContainer, 
                            jsonResponse.errors || jsonResponse.message || 'Failed to add product type');
                        showToast('Error: ' + (jsonResponse.message || 'Failed to add product type'), 'error');
                        return;
                    }

                    if (jsonResponse.success) {
                        showToast(jsonResponse.message || 'Product type added successfully!', 'success');
                        form.reset();
                        hideTypeDuplicateWarning();
                        
                        // Dynamically add the new product type to the TOP of the list
                        if (jsonResponse.type) {
                            const productTypeList = document.getElementById('productTypesList');
                            if (productTypeList) {
                                const newProductTypeCard = document.createElement('div');
                                newProductTypeCard.className = 'col-md-12 mb-3';
                                newProductTypeCard.innerHTML = `
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded new-type-highlight">
                                        <div class="d-flex align-items-center">
                                            <div class="product-type-icon me-3 bg-info" style="position: relative;">
                                                <i class="fas fa-tag"></i>
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                                    NEW
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-capitalize">
                                                    ${jsonResponse.type.name}
                                                    <span class="badge bg-danger ms-1 badge-new">NEW</span>
                                                </h6>
                                                ${jsonResponse.type.descriptions ? `<small class="text-muted d-block">${jsonResponse.type.descriptions}</small>` : ''}
                                                <small class="text-muted">0 products • Created: Just now</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger delete-product-type" 
                                                data-id="${jsonResponse.type.id}" 
                                                data-name="${jsonResponse.type.name}"
                                                title="Delete ${jsonResponse.type.name}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                `;
                                // Insert at the top of the list
                                productTypeList.insertBefore(newProductTypeCard, productTypeList.firstChild);
                            }
                        }
                    } else {
                        showValidationErrors(errorContainer, 
                            jsonResponse.message || 'Unknown error occurred');
                        showToast('Error: ' + (jsonResponse.message || 'Unknown error occurred'), 'error');
                    }
                } catch (error) {
                    console.error('Network error:', error);
                    showValidationErrors(errorContainer, 'Network error occurred: ' + error.message);
                    showToast('Error: Network error occurred', 'error');
                } finally {
                    // Re-enable button and hide spinner
                    if (btn) btn.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                }
            });
        }

        // DELETE: Product Type (ALL types including regular and special)
        document.body.addEventListener('click', function(e) {
            const delBtn = e.target.closest('.delete-product-type');
            if (!delBtn) return;
            
            const typeId = delBtn.getAttribute('data-id');
            const typeName = delBtn.getAttribute('data-name');
            
            if (confirm(`Are you sure you want to delete the product type "${typeName}" and all associated products? This action cannot be undone.`)) {
                deleteProductType(typeId, typeName);
            }
        });

        async function deleteProductType(typeId, typeName) {
            try {
                const response = await fetch(`/admin/products/delete-type/${typeId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const jsonResponse = await response.json();

                if (!response.ok) {
                    showToast('Error: ' + (jsonResponse.message || 'Failed to delete product type'), 'error');
                    return;
                }

                if (jsonResponse.success) {
                    showToast(jsonResponse.message || 'Product type deleted successfully!', 'success');
                    // Remove the element dynamically
                    const productTypeElement = document.querySelector(`[data-id="${typeId}"]`).closest('.col-md-12');
                    if (productTypeElement) {
                        productTypeElement.remove();
                    }
                } else {
                    showToast('Error: ' + (jsonResponse.message || 'Failed to delete product type'), 'error');
                }
            } catch (err) {
                console.error('Network error:', err);
                showToast('Error: Network error occurred while deleting product type', 'error');
            }
        }
    });
</script>
@endsection
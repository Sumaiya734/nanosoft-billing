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
<<<<<<< HEAD
                    <form id="createProductTypeForm" method="POST" action="{{ route('admin.products.add-type') }}">
=======
                    <form id="createProductTypeForm">
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                        @csrf
                        
                        <div id="createErrors" class="alert alert-danger d-none"></div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Type Name *</label>
                            <input type="text" 
                                   name="name" 
<<<<<<< HEAD
=======
                                   id="productTypeName"
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                   class="form-control form-control-lg" 
                                   placeholder="Enter product type (e.g., premium, business, basic)" 
                                   required
                                   autofocus>
                            <div class="form-text">
<<<<<<< HEAD
                                This will be used as the product type when creating new products.
=======
                                This will be used as the product type when creating new products. Must be unique.
                            </div>
                            <div id="typeDuplicateWarning" class="text-warning small mt-1 d-none">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <span id="typeDuplicateMessage"></span>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
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
<<<<<<< HEAD
                            <strong>How it works:</strong> Product types categorize your products. You can assign products to these types when creating or editing them.
=======
                            <strong>Note:</strong> Newly created product types will appear at the top of the list.
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
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
<<<<<<< HEAD
                    <div class="row">
=======
                    <div class="row" id="productTypesList">
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                        @foreach($productTypes as $type)
                        <div class="col-md-12 mb-3">
                            <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                <div class="d-flex align-items-center">
<<<<<<< HEAD
                                    <div class="product-type-icon me-3 {{ $type->name === 'regular' ? 'bg-primary' : ($type->name === 'special' ? 'bg-warning' : 'bg-success') }}">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-capitalize">{{ $type->name }}</h6>
                                        @if($type->descriptions)
                                            <small class="text-muted d-block">{{ $type->descriptions }}</small>
                                        @endif
                                        <small class="text-muted">{{ $productCounts[$type->name] ?? 0 }} products</small>
=======
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
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-danger delete-product-type" 
                                        data-id="{{ $type->id }}" 
                                        data-name="{{ $type->name }}"
<<<<<<< HEAD
                                        title="Delete"
                                        {{ in_array($type->name, ['regular', 'special']) ? 'disabled' : '' }}>
=======
                                        title="Delete {{ $type->name }}">
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
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
<<<<<<< HEAD
=======
        position: relative;
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
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
<<<<<<< HEAD
=======

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
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
        // CSRF token with error handling
        let csrfToken = null;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            csrfToken = csrfMeta.getAttribute('content');
        } else {
            console.error('CSRF meta tag not found');
        }

        // Toast helper with color coding
        function showToast(message, type = 'success') {
            // Map types to Bootstrap color classes
=======
        // CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Toast helper
        function showToast(message, type = 'success') {
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
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
<<<<<<< HEAD

            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        }

        // Utility to show validation errors
=======
            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        }

        // Show validation errors
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
        function showValidationErrors(containerEl, errors) {
            if (!containerEl) return;
            containerEl.classList.remove('d-none');
            
            let errorHtml = '';
            if (typeof errors === 'string') {
                errorHtml = errors;
            } else if (errors.message) {
                errorHtml = errors.message;
            } else if (errors.errors) {
<<<<<<< HEAD
                // Laravel validation errors
                errorHtml = Object.values(errors.errors).flat().map(e => `<div>• ${e}</div>`).join('');
            } else {
                // Other error formats
=======
                errorHtml = Object.values(errors.errors).flat().map(e => `<div>• ${e}</div>`).join('');
            } else {
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                errorHtml = Object.values(errors).flat().map(e => `<div>• ${e}</div>`).join('');
            }
            
            containerEl.innerHTML = errorHtml;
        }

<<<<<<< HEAD
        // CREATE: submit form
        const createForm = document.getElementById('createproductTypeForm');
        if (createForm) {
            createForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('product type form submit event triggered');
                
                const form = e.target;
                const btn = document.getElementById('createproductTypeBtn');
=======
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
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                const spinner = btn?.querySelector('.spinner-border');
                const errorContainer = document.getElementById('createErrors');
                
                // Disable button and show spinner
                if (btn) btn.disabled = true;
                if (spinner) spinner.classList.remove('d-none');
                if (errorContainer) errorContainer.classList.add('d-none');

                const formData = new FormData(form);
<<<<<<< HEAD
                const productName = formData.get('name');
                const productDescriptions = formData.get('descriptions');
                
                console.log('product type name:', productName);
                console.log('product type descriptions:', productDescriptions);

=======
                
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                try {
                    const response = await fetch('{{ route("admin.products.add-type") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
<<<<<<< HEAD
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: productName,
                            descriptions: productDescriptions,
                            _token: csrfToken
                        })
                    });

                    console.log('Response status:', response.status);
                    const jsonResponse = await response.json();
                    console.log('Response JSON:', jsonResponse);

                    if (!response.ok) {
                        const errorMessage = jsonResponse.errors ? 
                            Object.values(jsonResponse.errors).flat().join(', ') : 
                            jsonResponse.message || 'Failed to add product type';
                        
                        showValidationErrors(errorContainer, errorMessage);
                        showToast('Error: ' + errorMessage, 'error');
=======
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const jsonResponse = await response.json();

                    if (!response.ok) {
                        showValidationErrors(errorContainer, 
                            jsonResponse.errors || jsonResponse.message || 'Failed to add product type');
                        showToast('Error: ' + (jsonResponse.message || 'Failed to add product type'), 'error');
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                        return;
                    }

                    if (jsonResponse.success) {
                        showToast(jsonResponse.message || 'Product type added successfully!', 'success');
                        form.reset();
<<<<<<< HEAD
                        
                        // Instead of reloading the page, dynamically add the new product type to the list
                        if (jsonResponse.type) {
                            console.log('Adding new product type to list:', jsonResponse.type);
                            const productTypeList = document.querySelector('.card-body .row');
                            if (productTypeList) {
                                console.log('Found product type list container');
                                const newProductTypeCard = document.createElement('div');
                                newProductTypeCard.className = 'col-md-12 mb-3';
                                newProductTypeCard.innerHTML = `
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="product-type-icon me-3 bg-success">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-capitalize">${jsonResponse.type.name}</h6>
                                                ${jsonResponse.type.descriptions ? `<small class="text-muted d-block">${jsonResponse.type.descriptions}</small>` : ''}
                                                <small class="text-muted">0 products</small>
=======
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
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger delete-product-type" 
                                                data-id="${jsonResponse.type.id}" 
                                                data-name="${jsonResponse.type.name}"
<<<<<<< HEAD
                                                title="Delete">
=======
                                                title="Delete ${jsonResponse.type.name}">
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                `;
<<<<<<< HEAD
                                productTypeList.appendChild(newProductTypeCard);
                                console.log('Successfully added new product type to list');
                            } else {
                                console.error('Could not find product type list container');
                            }
                        }
                        
                        // Remove the page reload that was causing navigation
                        // The success message will remain visible as a toast popup
                    } else {
                        const errorMessage = jsonResponse.message || 'Unknown error occurred';
                        showValidationErrors(errorContainer, errorMessage);
                        showToast('Error: ' + errorMessage, 'error');
                    }
                } catch (error) {
                    console.error('Network error:', error);
                    const errorMessage = 'Network error occurred: ' + error.message;
                    showValidationErrors(errorContainer, errorMessage);
                    showToast('Error: ' + errorMessage, 'error');
=======
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
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                } finally {
                    // Re-enable button and hide spinner
                    if (btn) btn.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                }
            });
<<<<<<< HEAD
        } else {
            console.error('product type form not found');
        }

        // DELETE: product type
        document.body.addEventListener('click', function(e) {
            const delBtn = e.target.closest('.delete-product-type');
            if (!delBtn) return;
=======
        }

        // DELETE: Product Type (ALL types including regular and special)
        document.body.addEventListener('click', function(e) {
            const delBtn = e.target.closest('.delete-product-type');
            if (!delBtn) return;
            
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
            const typeId = delBtn.getAttribute('data-id');
            const typeName = delBtn.getAttribute('data-name');
            
            if (confirm(`Are you sure you want to delete the product type "${typeName}" and all associated products? This action cannot be undone.`)) {
<<<<<<< HEAD
                deleteproductType(typeId, typeName);
            }
        });

        async function deleteproductType(typeId, typeName) {
=======
                deleteProductType(typeId, typeName);
            }
        });

        async function deleteProductType(typeId, typeName) {
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
            try {
                const response = await fetch(`/admin/products/delete-type/${typeId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
<<<<<<< HEAD
                        'Accept': 'application/json'
=======
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                    }
                });

                const jsonResponse = await response.json();

                if (!response.ok) {
<<<<<<< HEAD
                    const errorMessage = jsonResponse.message || 'Failed to delete product type';
                    showToast('Error: ' + errorMessage, 'error');
=======
                    showToast('Error: ' + (jsonResponse.message || 'Failed to delete product type'), 'error');
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                    return;
                }

                if (jsonResponse.success) {
                    showToast(jsonResponse.message || 'Product type deleted successfully!', 'success');
<<<<<<< HEAD
                    // Instead of reloading the page, remove the element dynamically
=======
                    // Remove the element dynamically
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                    const productTypeElement = document.querySelector(`[data-id="${typeId}"]`).closest('.col-md-12');
                    if (productTypeElement) {
                        productTypeElement.remove();
                    }
<<<<<<< HEAD
                    // Remove the page reload that was causing navigation
                } else {
                    const errorMessage = jsonResponse.message || 'Failed to delete product type';
                    showToast('Error: ' + errorMessage, 'error');
=======
                } else {
                    showToast('Error: ' + (jsonResponse.message || 'Failed to delete product type'), 'error');
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                }
            } catch (err) {
                console.error('Network error:', err);
                showToast('Error: Network error occurred while deleting product type', 'error');
            }
        }
<<<<<<< HEAD

=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
    });
</script>
@endsection
@extends('layouts.admin')

@section('title', 'Create New Package - Admin Dashboard')

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
                <i class="fas fa-plus me-2 text-primary"></i>Create New Package
            </h2>
            <p class="text-muted mb-0">Add a new internet package to your offerings</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Packages
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Create Package Form Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cube me-2"></i>Package Details
                    </h5>
                </div>
                <div class="card-body">
                    <form id="createPackageForm" method="POST" action="{{ route('admin.packages.store') }}">
                        @csrf
                        
                        <div id="createErrors" class="alert alert-danger d-none"></div>

                        <!-- Package Name -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Package Name *</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control form-control-lg" 
                                   placeholder="e.g., Basic Plan, Premium Speed, Business Package" 
                                   required
                                   autofocus>
                            <div class="form-text">
                                Choose a descriptive name for your package.
                            </div>
                        </div>

                        <!-- Package Type -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Package Type *</label>
                            <select name="package_type_id" class="form-control form-control-lg" required>
                                <option value="">Select Package Type</option>
                                @foreach($packageTypes as $type)
                                    <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                Select the category for this package.
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
                            <label class="form-label fw-semibold">Package Description *</label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Describe the package features, speed, benefits, and any limitations..."
                                      required></textarea>
                            <div class="form-text">
                                Provide detailed information about what this package includes.
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
                                Add key features that make this package attractive to customers.
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Tip:</strong> Make sure to provide clear and accurate information about your package to help customers make informed decisions.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="createPackageBtn">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                <i class="fas fa-plus me-2"></i>Create Package
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Package Preview -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>Package Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div id="packagePreview" class="text-muted text-center py-4">
                        <i class="fas fa-cube fa-3x mb-3"></i>
                        <p>Your package preview will appear here as you fill out the form.</p>
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

    .package-preview-item {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }

    .package-preview-item h6 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CSRF token with error handling
        let csrfToken = null;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            csrfToken = csrfMeta.getAttribute('content');
        } else {
            console.error('CSRF meta tag not found');
        }

        // Toast helper
        function showToast(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
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

        // Utility to show validation errors
        function showValidationErrors(containerEl, errors) {
            if (!containerEl) return;
            containerEl.classList.remove('d-none');
            
            let errorHtml = '';
            if (typeof errors === 'string') {
                errorHtml = errors;
            } else if (errors.message) {
                errorHtml = errors.message;
            } else if (errors.errors) {
                // Laravel validation errors
                errorHtml = Object.values(errors.errors).flat().map(e => `<div>• ${e}</div>`).join('');
            } else {
                // Other error formats
                errorHtml = Object.values(errors).flat().map(e => `<div>• ${e}</div>`).join('');
            }
            
            containerEl.innerHTML = errorHtml;
        }

        // Feature management
        const features = [];
        
        document.querySelector('.add-feature-btn')?.addEventListener('click', function() {
            const featureInput = document.querySelector('.feature-input');
            const featureText = featureInput.value.trim();
            
            if (featureText) {
                features.push(featureText);
                updateFeaturesDisplay();
                featureInput.value = '';
            }
        });

        function updateFeaturesDisplay() {
            const container = document.querySelector('.features-container');
            // Clear existing features except the input
            const inputGroup = container.querySelector('.input-group');
            container.innerHTML = '';
            container.appendChild(inputGroup);
            
            // Add feature tags
            features.forEach((feature, index) => {
                const tag = document.createElement('div');
                tag.className = 'feature-tag';
                tag.innerHTML = `
                    ${feature}
                    <span class="remove-feature" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </span>
                `;
                container.appendChild(tag);
            });

            // Add remove event listeners
            document.querySelectorAll('.remove-feature').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    features.splice(index, 1);
                    updateFeaturesDisplay();
                });
            });
        }

        // Package preview
        function updatePackagePreview() {
            const name = document.querySelector('input[name="name"]').value;
            const typeSelect = document.querySelector('select[name="package_type_id"]');
            const type = typeSelect.options[typeSelect.selectedIndex]?.text || 'Not selected';
            const price = document.querySelector('input[name="monthly_price"]').value;
            const description = document.querySelector('textarea[name="description"]').value;

            const preview = document.getElementById('packagePreview');
            
            if (!name && !price && !description) {
                preview.innerHTML = `
                    <i class="fas fa-cube fa-3x mb-3 text-muted"></i>
                    <p class="text-muted">Your package preview will appear here as you fill out the form.</p>
                `;
                return;
            }

            let previewHtml = `
                <div class="package-preview-item">
                    <h6 class="fw-bold">${name || 'Package Name'}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Type:</strong> ${type}<br>
                            <strong>Price:</strong> ৳${price || '0.00'}/month
                        </div>
                        <div class="col-md-6">
                            <strong>Description:</strong><br>
                            <small>${description || 'No description provided'}</small>
                        </div>
                    </div>
            `;

            if (features.length > 0) {
                previewHtml += `
                    <div class="mt-3">
                        <strong>Features:</strong><br>
                        <div class="mt-2">
                `;
                features.forEach(feature => {
                    previewHtml += `<span class="badge bg-primary me-1 mb-1">${feature}</span>`;
                });
                previewHtml += `</div></div>`;
            }

            previewHtml += `</div>`;
            preview.innerHTML = previewHtml;
        }

        // Add event listeners for real-time preview
        document.querySelectorAll('input[name="name"], input[name="monthly_price"], textarea[name="description"]').forEach(input => {
            input.addEventListener('input', updatePackagePreview);
        });

        const typeSelect = document.querySelector('select[name="package_type_id"]');
        if (typeSelect) {
            typeSelect.addEventListener('change', updatePackagePreview);
        }

        // CREATE: submit form
        const createForm = document.getElementById('createPackageForm');
        if (createForm) {
            console.log('Form element found, attaching submit event listener');
            
            createForm.addEventListener('submit', async function(e) {
                console.log('Form submit event triggered');
                
                // Check if this is an AJAX submission
                if (e.detail && e.detail.ajax === false) {
                    // Allow normal form submission
                    console.log('Allowing normal form submission');
                    return true;
                }
                
                e.preventDefault();
                console.log('Default form submission prevented');
                
                const form = e.target;
                const btn = document.getElementById('createPackageBtn');
                const spinner = btn?.querySelector('.spinner-border');
                
                if (btn) btn.disabled = true;
                if (spinner) spinner.classList.remove('d-none');
                document.getElementById('createErrors')?.classList.add('d-none');

                const formData = new FormData(form);
                
                // Add features to form data
                features.forEach((feature, index) => {
                    formData.append(`features[${index}]`, feature);
                });

                // Log form data for debugging
                console.log('Form Data:', Object.fromEntries(formData));

                try {
                    console.log('Sending AJAX request to:', '{{ route("admin.packages.store") }}');
                    console.log('CSRF Token:', csrfToken);
                    
                    const res = await fetch('{{ route("admin.packages.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    console.log('Response received:', res);
                    const json = await res.json();
                    console.log('Response JSON:', json);

                    if (!res.ok) {
                        showValidationErrors(document.getElementById('createErrors'), json.errors || json.message || 'Failed to create package');
                        return;
                    }

                    if (json.success) {
                        showToast(json.message || 'Package created successfully!', 'success');
                        form.reset();
                        features.length = 0;
                        updateFeaturesDisplay();
                        updatePackagePreview();
                        
                        // Redirect to packages list after success
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.packages.index") }}';
                        }, 2000);
                    } else {
                        showValidationErrors(document.getElementById('createErrors'), json.message || 'Unknown error occurred');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showValidationErrors(document.getElementById('createErrors'), 'Network error occurred: ' + error.message);
                    
                    // Fallback: submit form normally if AJAX fails
                    console.log('AJAX failed, falling back to normal form submission');
                    const fallbackEvent = new CustomEvent('submit', {
                        detail: { ajax: false },
                        cancelable: true
                    });
                    form.dispatchEvent(fallbackEvent);
                } finally {
                    if (btn) btn.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                }
            });
        } else {
            console.error('Form element not found!');
        }

        // Initialize preview
        updatePackagePreview();
    });
</script>
@endsection

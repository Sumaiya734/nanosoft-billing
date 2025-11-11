@extends('layouts.admin')

@section('title', 'Package Management - Admin Dashboard')

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
                <i class="fas fa-cube me-2 text-primary"></i>Package Management
            </h2>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" id="exportBtn">
                <i class="fas fa-download me-1"></i>Export
            </button>
            <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Package
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Packages</h6>
                            <h3 class="mb-0">{{ $stats['total_packages'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar-sm bg-primary rounded-circle text-white d-flex align-items-center justify-content-center">
                            <i class="fas fa-cubes"></i>
                        </div>
                    </div>
                    <p class="text-success mt-3 mb-0">
                        <i class="fas fa-check-circle me-1"></i> All active packages
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Regular Packages</h6>
                            <h3 class="mb-0">{{ $stats['regular_packages'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar-sm bg-success rounded-circle text-white d-flex align-items-center justify-content-center">
                            <i class="fas fa-bolt"></i>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        From ৳{{ number_format($stats['price_range_regular']['min'] ?? 0) }} to ৳{{ number_format($stats['price_range_regular']['max'] ?? 0) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Special Packages</h6>
                            <h3 class="mb-0">{{ $stats['special_packages'] ?? 0 }}</h3>
                        </div>
                        <div class="avatar-sm bg-warning rounded-circle text-white d-flex align-items-center justify-content-center">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        From ৳{{ number_format($stats['price_range_special']['min'] ?? 0) }} to ৳{{ number_format($stats['price_range_special']['max'] ?? 0) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Active Customers</h6>
                            <h3 class="mb-0">{{ $stats['active_customers'] ?? 'N/A' }}</h3>
                        </div>
                        <div class="avatar-sm bg-info rounded-circle text-white d-flex align-items-center justify-content-center">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <p class="text-success mt-3 mb-0">
                        <i class="fas fa-arrow-up me-1"></i> 12 new this month
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Packages Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>All Packages
            </h5>

            <div class="d-flex gap-2 align-items-center">
                <input type="text" class="form-control form-control-sm search-box" placeholder="Search packages..." style="min-width: 200px;">
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary filter-btn active" data-type="all">All</button>
                    <button class="btn btn-sm btn-outline-secondary filter-btn" data-type="regular">Regular</button>
                    <button class="btn btn-sm btn-outline-secondary filter-btn" data-type="special">Special</button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th>Package Name</th>
                            <th>Package Type</th>
                            <th>Description</th>
                            <th width="120" class="text-end">Price</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr data-type="{{ $package->package_type }}" id="package-row-{{ $package->p_id }}">
                            <td class="fw-bold">{{ $package->p_id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="package-icon me-3 {{ $package->isRegular() ? 'bg-primary' : 'bg-warning' }}">
                                        <i class="fas {{ $package->isRegular() ? 'fa-wifi' : 'fa-star' }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $package->name }}</h6>
                                        <small class="text-muted">{{ $package->type->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $typeName = $package->type->name ?? 'Unknown';
                                    $badgeClass = match(strtolower($typeName)) {
                                        'regular' => 'bg-primary',
                                        'special' => 'bg-warning text-dark',
                                        'premium' => 'bg-success',
                                        'enterprise' => 'bg-info',
                                        'custom' => 'bg-purple text-white',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($typeName) }}</span>
                            </td>
                            <td>
                                <p class="mb-1">{{ \Illuminate\Support\Str::limit($package->description, 60) }}</p>
                                <small class="text-muted">Created: {{ $package->created_at ? $package->created_at->format('M d, Y') : 'N/A' }}</small>
                            </td>
                           
                            <td class="text-end">
                                <h6 class="text-success mb-0">৳{{ number_format($package->monthly_price, 2) }}<small class="text-muted">/month</small></h6>
                            </td>
                            <td class="text-center action-column">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary edit-package" 
                                            data-id="{{ $package->p_id }}" 
                                            data-name="{{ $package->name }}"
                                            title="Edit Package">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-none d-md-inline ms-1">Edit</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger delete-package" 
                                            data-id="{{ $package->p_id }}" 
                                            data-name="{{ $package->name }}"
                                            title="Delete Package">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-md-inline ms-1">Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No packages found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Showing {{ $packages->count() }} of {{ $stats['total_packages'] ?? $packages->count() }} packages
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Package Modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editPackageForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="p_id" id="edit_p_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="editPackageModalBody">
                    <div class="text-center py-4" id="editLoading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading package details...</p>
                    </div>

                    <div id="editErrors" class="alert alert-danger d-none"></div>

                    <div id="editFields" style="display:none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Package Name *</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Package Type *</label>
                                <select name="package_type_id" id="edit_package_type_id" class="form-control" required>
                                    <option value="">Select Package Type</option>
                                    @foreach($packageTypes as $type)
                                        <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (৳/month) *</label>
                                <input type="number" name="monthly_price" id="edit_monthly_price" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updatePackageBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Update Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .package-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .bg-orange {
        background-color: #fd7e14 !important;
    }

    .stat-card {
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .avatar-sm {
        width: 50px;
        height: 50px;
    }

    .filter-btn.active {
        background-color: #4361ee;
        color: white;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #2b2d42;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
        padding: 16px 12px;
    }

    .action-column {
        white-space: nowrap;
    }

    .action-column .btn-group {
        display: inline-flex;
    }

    .action-column .btn {
        min-width: 36px;
        transition: all 0.2s ease;
    }

    .action-column .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .action-column .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .action-column {
            position: sticky;
            right: 0;
            background-color: white;
            box-shadow: -2px 0 5px rgba(0,0,0,0.05);
        }

        .table thead th:last-child {
            position: sticky;
            right: 0;
            background-color: #f8f9fa;
            box-shadow: -2px 0 5px rgba(0,0,0,0.05);
        }
    }

    @media (max-width: 576px) {
        .card-header {
            flex-direction: column;
            gap: 10px;
        }

        .card-header .d-flex {
            width: 100%;
            flex-direction: column;
        }

        .search-box {
            width: 100% !important;
            min-width: auto !important;
        }

        .action-column .btn span {
            display: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    console.log('🚀 Package management script loaded');
    
    (function() {
        console.log('📦 Initializing package management...');
        
        // CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('🔑 CSRF Token:', csrfToken ? 'Found' : 'Missing');

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
            if (typeof errors === 'string') {
                containerEl.innerHTML = errors;
                return;
            }
            if (errors.message) {
                containerEl.innerHTML = errors.message;
                return;
            }
            const list = Object.values(errors).flat().map(e => `<div>• ${e}</div>`).join('');
            containerEl.innerHTML = list;
        }

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                filterPackages(this.getAttribute('data-type'));
            });
        });

        function filterPackages(type) {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const rowType = row.getAttribute('data-type');
                const match = !type || type === 'all' || rowType === type;
                row.style.display = match ? '' : 'none';
            });
        }

        // Search functionality
        document.querySelector('.search-box')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // EDIT: Open modal with improved error handling
        console.log('✅ Edit button listener attached');
        
        document.body.addEventListener('click', function(e) {
            console.log('👆 Click detected on:', e.target);
            
            const editBtn = e.target.closest('.edit-package');
            if (editBtn) {
                console.log('✏️ Edit button clicked!', editBtn);
                e.preventDefault();
                const pId = editBtn.getAttribute('data-id');
                const packageName = editBtn.getAttribute('data-name');
                console.log('📝 Package ID:', pId, 'Name:', packageName);
                
                // Disable button temporarily
                editBtn.disabled = true;
                const originalHtml = editBtn.innerHTML;
                editBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                openEditModal(pId, packageName).finally(() => {
                    editBtn.disabled = false;
                    editBtn.innerHTML = originalHtml;
                });
            }
        });

        async function openEditModal(pId, packageName) {
            const errorsEl = document.getElementById('editErrors');
            const loadingEl = document.getElementById('editLoading');
            const fieldsEl = document.getElementById('editFields');
            
            errorsEl.classList.add('d-none');
            loadingEl.style.display = '';
            fieldsEl.style.display = 'none';

            const modalEl = document.getElementById('editPackageModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            try {
                const url = `{{ url('admin/packages') }}/${pId}`;
                console.log('📡 Fetching package from:', url);
                
                const res = await fetch(url, {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!res.ok) {
                    const errorData = await res.json().catch(() => ({}));
                    throw new Error(errorData.message || `Failed to fetch package (${res.status})`);
                }
                
                const pkg = await res.json();

                // Populate fields with validation
                document.getElementById('edit_p_id').value = pkg.p_id || pId;
                document.getElementById('edit_name').value = pkg.name || '';
                document.getElementById('edit_package_type_id').value = pkg.package_type_id || '';
                document.getElementById('edit_monthly_price').value = pkg.monthly_price || '';
                document.getElementById('edit_description').value = pkg.description || '';

                loadingEl.style.display = 'none';
                fieldsEl.style.display = '';
                
                // Update modal title with package name
                document.getElementById('editPackageModalLabel').textContent = `Edit Package: ${pkg.name || packageName}`;
            } catch (err) {
                console.error('Error loading package:', err);
                loadingEl.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${err.message || 'Failed to load package details. Please try again.'}
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                `;
                showToast('Failed to load package details', 'danger');
            }
        }

        // UPDATE: submit update
        document.getElementById('editPackageForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            await submitUpdate();
        });

        async function submitUpdate() {
            const form = document.getElementById('editPackageForm');
            const pId = document.getElementById('edit_p_id').value;
            const btn = document.getElementById('updatePackageBtn');
            const spinner = btn.querySelector('.spinner-border');
            
            btn.disabled = true;
            spinner.classList.remove('d-none');
            document.getElementById('editErrors').classList.add('d-none');

            const formData = new FormData(form);
            const url = `{{ url('admin/packages') }}/${pId}`;
            console.log('📡 Updating package at:', url);

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'PUT',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const json = await res.json();

                if (!res.ok) {
                    showValidationErrors(document.getElementById('editErrors'), json.errors || json.message || 'Failed to update package');
                    return;
                }

                if (json.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editPackageModal')).hide();
                    showToast(json.message || 'Package updated successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showValidationErrors(document.getElementById('editErrors'), json.message || 'Unknown error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
                showValidationErrors(document.getElementById('editErrors'), 'Network error occurred');
            } finally {
                btn.disabled = false;
                spinner.classList.add('d-none');
            }
        }

        // DELETE: package with improved confirmation and feedback
        console.log('✅ Delete button listener attached');
        
        document.body.addEventListener('click', function(e) {
            const delBtn = e.target.closest('.delete-package');
            if (!delBtn) return;
            
            console.log('🗑️ Delete button clicked!', delBtn);
            e.preventDefault();
            const pId = delBtn.getAttribute('data-id');
            const packageName = delBtn.getAttribute('data-name');
            console.log('📝 Package ID:', pId, 'Name:', packageName);
            
            // Custom confirmation with package name
            const confirmMsg = `Are you sure you want to delete "${packageName}"?\n\nThis action cannot be undone and will remove all associated data.`;
            
            if (confirm(confirmMsg)) {
                console.log('✅ User confirmed deletion');
                // Disable button and show loading state
                delBtn.disabled = true;
                const originalHtml = delBtn.innerHTML;
                delBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                deletePackage(pId, packageName).finally(() => {
                    delBtn.disabled = false;
                    delBtn.innerHTML = originalHtml;
                });
            } else {
                console.log('❌ User cancelled deletion');
            }
        });

        async function deletePackage(pId, packageName) {
            try {
                const url = `{{ url('admin/packages') }}/${pId}`;
                console.log('📡 Deleting package at:', url);
                
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const json = await res.json().catch(() => ({}));

                if (!res.ok) {
                    showToast(json.message || `Failed to delete package (${res.status})`, 'danger');
                    return;
                }

                if (json.success) {
                    showToast(json.message || `Package "${packageName}" deleted successfully!`, 'success');
                    
                    // Animate row removal
                    const row = document.getElementById(`package-row-${pId}`);
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease-out';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            updatePackageCount();
                        }, 300);
                    }
                } else {
                    showToast(json.message || 'Failed to delete package', 'danger');
                }
            } catch (err) {
                console.error('Error deleting package:', err);
                showToast('Network error: Failed to delete package', 'danger');
            }
        }

        // Helper function to update package count
        function updatePackageCount() {
            const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])').length;
            const footerDiv = document.querySelector('.card-footer div');
            if (footerDiv) {
                footerDiv.textContent = `Showing ${visibleRows} of ${visibleRows} packages`;
            }
            
            // Show empty state if no packages
            if (visibleRows === 0) {
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">No packages found.</td></tr>';
            }
        }

        // EXPORT functionality
        document.getElementById('exportBtn')?.addEventListener('click', function() {
            const rows = Array.from(document.querySelectorAll('table tbody tr:not([style*="display: none"])'));
            const csv = [];
            csv.push(['ID', 'Name', 'Type', 'Price', 'Description'].join(','));
            
            rows.forEach(row => {
                const cols = row.querySelectorAll('td');
                if (cols.length >= 6) {
                    const rowData = [
                        `"${cols[0].textContent.trim()}"`,
                        `"${cols[1].querySelector('h6') ? cols[1].querySelector('h6').textContent.trim() : cols[1].textContent.trim()}"`,
                        `"${cols[2].textContent.trim()}"`,
                        `"${cols[4].textContent.trim().replace('/month','').replace('৳','').trim()}"`,
                        `"${cols[3].querySelector('p') ? cols[3].querySelector('p').textContent.trim() : cols[3].textContent.trim()}"`
                    ];
                    csv.push(rowData.join(','));
                }
            });
            
            const csvContent = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv.join('\n'));
            const a = document.createElement('a');
            a.setAttribute('href', csvContent);
            a.setAttribute('download', `packages_export_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(a);
            a.click();
            a.remove();
            showToast('Export started successfully!', 'success');
        });

        // Initialize filter
        filterPackages('all');
        
        console.log('✅ Package management initialized successfully');
        console.log('📊 Edit buttons found:', document.querySelectorAll('.edit-package').length);
        console.log('📊 Delete buttons found:', document.querySelectorAll('.delete-package').length);

    })();
</script>
@endsection
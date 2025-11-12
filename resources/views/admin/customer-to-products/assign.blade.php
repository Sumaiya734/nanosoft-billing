@extends('layouts.admin')

@section('title', 'Assign Product')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="page-title"><i class="fas fa-plus-circle me-2"></i>Assign Products to Customer</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.customer-to-products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
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

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-tag me-2"></i>Product Assignment Form</h5>
                </div>
                <div class="card-body">
                    <form id="assignProductForm" action="{{ route('admin.customer-to-products.store') }}" method="POST">
                        @csrf

                        <!-- Customer Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">Select Customer *</label>

                                <div class="mb-3">
                                    <input type="text" class="form-control" id="customerSearch"
                                           placeholder="Search customers by name, phone, email, or ID..."
                                           autocomplete="off">
                                    <div class="form-text">Start typing to show customer list. Click on a customer to select.</div>
                                </div>

                                <div id="customerResults" class="customer-results-container" style="max-height:300px;overflow-y:auto;display:none;">
                                    @foreach($customers as $customer)
                                        <div class="customer-result-item"
                                             data-customer-id="{{ $customer->c_id }}"
                                             data-customer-name="{{ $customer->name }}"
                                             data-customer-phone="{{ $customer->phone ?? 'No phone' }}"
                                             data-customer-email="{{ $customer->email ?? 'No email' }}"
                                             data-customer-customerid="{{ $customer->customer_id }}">
                                            <div class="customer-name">{{ $customer->name }}</div>
                                            <div class="customer-details">
                                                @if($customer->phone)
                                                    <i class="fas fa-phone me-1"></i>{{ $customer->phone }} •
                                                @endif
                                                <i class="fas fa-id-card me-1"></i>ID: {{ $customer->customer_id }}
                                                @if($customer->email)
                                                    • <i class="fas fa-envelope me-1"></i>{{ $customer->email }}
                                                @endif
                                            </div>
                                            <div class="customer-address small text-muted mt-1">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $customer->address ?? 'No address provided' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div id="selectedCustomer" class="selected-customer-card mt-3" style="display:none;">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1" id="selectedCustomerName"></h6>
                                                    <p class="mb-1 text-muted" id="selectedCustomerDetails"></p>
                                                    <small class="text-muted" id="selectedCustomerId"></small>
                                                </div>
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        onclick="clearCustomerSelection()">
                                                    <i class="fas fa-times"></i> Change
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="customer_id" id="customerId" value="{{ old('customer_id') }}">

                                @error('customer_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Products Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label fw-bold">Select Products *</label>
                                    <button type="button" class="btn btn-primary btn-sm" id="addProductBtn">
                                        <i class="fas fa-plus me-1"></i>Add Another Product
                                    </button>
                                </div>

                                <div class="products-container" id="productsContainer">
                                    <!-- Initial Product Row - NO DELETE BUTTON -->
                                    <div class="product-row mb-3" data-index="0">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Product 1 *</label>
                                                <select class="form-select product-select @error('products.0.product_id') is-invalid @enderror"
                                                        name="products[0][product_id]" data-index="0" required>
                                                    <option value="">Select a product...</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->p_id }}"
                                                                data-price="{{ $product->monthly_price }}"
                                                                data-type="{{ $product->product_type }}"
                                                                {{ old('products.0.product_id') == $product->p_id ? 'selected' : '' }}>
                                                            {{ $product->name }} - ৳{{ number_format($product->monthly_price, 2) }}/month
                                                            ({{ ucfirst($product->product_type) }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('products.0.product_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Billing cycle *</label>
                                                <select class="form-select billing-months @error('products.0.billing_cycle_months') is-invalid @enderror"
                                                        name="products[0][billing_cycle_months]" data-index="0" required>
                                                    <option value="1" {{ old('products.0.billing_cycle_months', '1') == '1' ? 'selected' : '' }}>1 Month</option>
                                                    <option value="2" {{ old('products.0.billing_cycle_months') == '2' ? 'selected' : '' }}>2 Months</option>
                                                    <option value="3" {{ old('products.0.billing_cycle_months') == '3' ? 'selected' : '' }}>3 Months</option>
                                                    <option value="6" {{ old('products.0.billing_cycle_months') == '6' ? 'selected' : '' }}>6 Months</option>
                                                    <option value="12" {{ old('products.0.billing_cycle_months') == '12' ? 'selected' : '' }}>12 Months</option>
                                                </select>
                                                @error('products.0.billing_cycle_months')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Price *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="number" class="form-control monthly-price @error('products.0.monthly_price') is-invalid @enderror"
                                                           name="products[0][monthly_price]" 
                                                           data-index="0" 
                                                           value="{{ old('products.0.monthly_price', '0') }}" 
                                                           min="0" step="0.01" required>
                                                </div>
                                                @error('products.0.monthly_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Assign Date *</label>
                                                <input type="date" class="form-control assign-date @error('products.0.assign_date') is-invalid @enderror"
                                                       name="products[0][assign_date]"
                                                       value="{{ old('products.0.assign_date', date('Y-m-d')) }}" data-index="0" required>
                                                @error('products.0.assign_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Due Day *</label>
                                                <select class="form-select due-day @error('products.0.due_day') is-invalid @enderror"
                                                        name="products[0][due_day]" data-index="0" required>
                                                    @for($i = 1; $i <= 28; $i++)
                                                        <option value="{{ $i }}" {{ old('products.0.due_day', date('j')) == $i ? 'selected' : '' }}>
                                                            {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('products.0.due_day')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Amount</label>
                                                <div class="product-amount" data-index="0">৳ 0</div>
                                            </div>

                                            <!-- REMOVED DELETE BUTTON FOR FIRST PRODUCT -->
                                            <div class="col-md-1">
                                                <!-- Empty column to maintain layout -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="summary-card">
                                    <h6 class="summary-title">Order Summary</h6>
                                    <div class="summary-details" id="summaryDetails">
                                        <div class="summary-row" id="productSummary0">
                                            <span>Product 1:</span><span>৳ 0</span>
                                        </div>
                                    </div>
                                    <div class="summary-divider"></div>
                                    <div class="summary-row total">
                                        <span class="fw-bold">Total Amount:</span>
                                        <span class="fw-bold" id="totalAmount">৳ 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg w-100" id="submitBtn" disabled>
                                    <i class="fas fa-check me-2"></i>Assign Products
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template for new product rows -->
<template id="productOptionsTemplate">
    @foreach($products as $product)
        <option value="{{ $product->p_id }}"
                data-price="{{ $product->monthly_price }}"
                data-type="{{ $product->product_type }}">
            {{ $product->name }} - ৳{{ number_format($product->monthly_price, 2) }}/month
            ({{ ucfirst($product->product_type) }})
        </option>
    @endforeach
</template>

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
    .product-row {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
    }
    .product-row:hover {
        border-color: #3498db;
        background: #f1f3f4;
    }
    .product-amount {
        font-weight: 700;
        color: #27ae60;
        font-size: 1rem;
        padding: 0.5rem;
        background: #fff;
        border-radius: 5px;
        text-align: center;
        border: 1px solid #dee2e6;
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .monthly-price {
        font-weight: 600;
        color: #495057;
    }
    .remove-product-btn {
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .summary-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        border: 2px solid #e9ecef;
    }
    .summary-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    .summary-row.total {
        border-bottom: none;
        font-size: 1.1rem;
        color: #2c3e50;
    }
    .summary-divider {
        height: 2px;
        background: #3498db;
        margin: 1rem 0;
    }
    .customer-results-container {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background: #fff;
    }
    .customer-result-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f8f9fa;
        cursor: pointer;
        transition: background 0.2s;
    }
    .customer-result-item:hover {
        background: #e9ecef;
    }
    .customer-result-item:last-child {
        border-bottom: none;
    }
    .selected-customer-card {
        margin-top: 15px;
    }
    .customer-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
    }
    .customer-details {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .customer-address {
        font-size: 0.8rem;
    }
    .product-select option:disabled {
        color: #ccc;
        background: #f8f9fa;
    }
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    .hidden {
        display: none !important;
    }
    .form-text {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let productCount = 1;
    let productAmounts = {};
    let selectedProducts = new Set();
    let availableIndexes = []; // Track available indexes for reuse

    const customerSearch = document.getElementById('customerSearch');
    const customerResults = document.getElementById('customerResults');
    const selectedCustomer = document.getElementById('selectedCustomer');
    const customerIdInput = document.getElementById('customerId');
    const submitBtn = document.getElementById('submitBtn');
    const productOptionsTemplate = document.getElementById('productOptionsTemplate');

    // Update submit button state
    function updateSubmitButton() {
        const hasCustomer = !!customerIdInput.value;
        const productSelects = Array.from(document.querySelectorAll('.product-select'));
        const hasProducts = productSelects.some(sel => sel.value && sel.value !== '');
        
        const shouldEnable = hasCustomer && hasProducts;
        
        submitBtn.disabled = !shouldEnable;
        
        if (shouldEnable) {
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        } else {
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        }
    }

    // Customer Search Functionality
    customerSearch.addEventListener('input', function () {
        const query = this.value.trim().toLowerCase();

        if (query.length === 0) {
            customerResults.style.display = 'none';
            document.querySelectorAll('.customer-result-item').forEach(item => {
                item.classList.remove('hidden');
            });
            return;
        }

        customerResults.style.display = 'block';
        let hasMatch = false;

        document.querySelectorAll('.customer-result-item').forEach(item => {
            const name = item.dataset.customerName.toLowerCase();
            const phone = item.dataset.customerPhone.toLowerCase();
            const email = item.dataset.customerEmail.toLowerCase();
            const customerId = item.dataset.customerCustomerid.toLowerCase();
            
            const matches = name.includes(query) || 
                          phone.includes(query) || 
                          email.includes(query) || 
                          customerId.includes(query);
            
            if (matches) {
                item.classList.remove('hidden');
                hasMatch = true;
            } else {
                item.classList.add('hidden');
            }
        });

        if (!hasMatch) {
            customerResults.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-search fa-2x mb-2 opacity-50"></i>
                    <div>No customers found for "${query}"</div>
                </div>`;
        }
    });

    // Customer Selection
    customerResults.addEventListener('click', function(e) {
        const customerItem = e.target.closest('.customer-result-item');
        if (customerItem && !customerItem.classList.contains('hidden')) {
            const id   = customerItem.dataset.customerId;
            const name = customerItem.dataset.customerName;
            const phone= customerItem.dataset.customerPhone;
            const email= customerItem.dataset.customerEmail;
            const custId = customerItem.dataset.customerCustomerid;
            selectCustomer(id, name, phone, email, custId);
        }
    });

    // Get next available index (reuse deleted indexes first)
    function getNextAvailableIndex() {
        if (availableIndexes.length > 0) {
            return availableIndexes.shift(); // Use the first available index
        } else {
            return productCount++; // Use a new index
        }
    }

    // Add Product Row
    document.getElementById('addProductBtn').addEventListener('click', function () {
        const idx = getNextAvailableIndex();
        const row = document.createElement('div');
        row.className = 'product-row mb-3';
        row.dataset.index = idx;

        // Calculate display number based on current product rows
        const currentRows = document.querySelectorAll('.product-row');
        const displayNumber = currentRows.length;

        row.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Product ${displayNumber} *</label>
                    <select class="form-select product-select" name="products[${idx}][product_id]" data-index="${idx}" required>
                        <option value="">Select a product...</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Monthly Price *</label>
                    <div class="input-group">
                        <span class="input-group-text">৳</span>
                        <input type="number" class="form-control monthly-price" 
                               name="products[${idx}][monthly_price]" 
                               data-index="${idx}" value="0" min="0" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Billing Months *</label>
                    <select class="form-select billing-months" name="products[${idx}][billing_cycle_months]" data-index="${idx}" required>
                        <option value="1">1 Month</option>
                        <option value="2">2 Months</option>
                        <option value="3" selected>3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="12">12 Months</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Assign Date *</label>
                    <input type="date" class="form-control assign-date" name="products[${idx}][assign_date]"
                           value="{{ date('Y-m-d') }}" data-index="${idx}" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Due Day *</label>
                    <select class="form-select due-day" name="products[${idx}][due_day]" data-index="${idx}" required>
                        @for($i = 1; $i <= 28; $i++)
                            <option value="{{ $i }}" {{ date('j') == $i ? 'selected' : '' }}>
                                {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Amount</label>
                    <div class="product-amount" data-index="${idx}">৳ 0</div>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-product-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>`;

        document.getElementById('productsContainer').appendChild(row);
        const select = row.querySelector('.product-select');
        select.innerHTML = '<option value="">Select a product...</option>' + productOptionsTemplate.innerHTML;
        updateProductOptions();

        // Add event listeners for the new row
        select.addEventListener('change', function() {
            updateSelectedProducts();
            autoFillMonthlyPrice(idx);
            calculateProductAmount(idx);
            
            // Check for existing products when selection changes
            const customerId = document.getElementById('customerId').value;
            if (customerId && this.value) {
                checkExistingProducts(customerId, this.value, idx);
            }
            
            updateSubmitButton();
        });
        
        row.querySelector('.monthly-price').addEventListener('input', function() {
            calculateProductAmount(idx);
            updateSubmitButton();
        });
        
        row.querySelector('.billing-months').addEventListener('change', function() {
            calculateProductAmount(idx);
            updateSubmitButton();
        });
        
        row.querySelector('.remove-product-btn').addEventListener('click', function() {
            removeProduct(idx);
        });

        // Add summary row
        const summary = document.createElement('div');
        summary.className = 'summary-row';
        summary.id = `productSummary${idx}`;
        summary.innerHTML = `<span>Product ${displayNumber}:</span><span>৳ 0</span>`;
        document.getElementById('summaryDetails').appendChild(summary);

        // Update all labels and summaries to be sequential
        updateAllProductLabels();
        updateSubmitButton();
    });

    // Remove Product
    function removeProduct(idx) {
        const productRow = document.querySelector(`.product-row[data-index="${idx}"]`);
        const productSelect = productRow?.querySelector('.product-select');
        
        if (productSelect && productSelect.value) {
            selectedProducts.delete(productSelect.value);
        }
        
        document.querySelector(`.product-row[data-index="${idx}"]`)?.remove();
        document.getElementById(`productSummary${idx}`)?.remove();
        delete productAmounts[idx];
        
        // Add the index back to available indexes for reuse
        if (idx > 0) { // Don't add index 0 (first product) to available indexes
            availableIndexes.push(idx);
            availableIndexes.sort((a, b) => a - b); // Keep sorted for consistent reuse
        }
        
        updateSelectedProducts();
        calculateTotal();
        
        // Update all labels and summaries to be sequential
        updateAllProductLabels();
        updateSubmitButton();
    }

    // Update all product labels and summaries to be sequential
    function updateAllProductLabels() {
        const productRows = document.querySelectorAll('.product-row');
        
        productRows.forEach((row, index) => {
            const displayNumber = index + 1; // Sequential numbering (1, 2, 3...)
            const idx = row.dataset.index;
            
            // Update the label
            const label = row.querySelector('.form-label');
            if (label) {
                label.textContent = `Product ${displayNumber} *`;
            }
            
            // Update the summary row
            const summaryRow = document.getElementById(`productSummary${idx}`);
            if (summaryRow) {
                const currentAmount = productAmounts[idx] || 0;
                summaryRow.innerHTML = `<span>Product ${displayNumber}:</span><span>৳ ${currentAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
            }
        });
    }

    // Unique Product Selection
    function updateProductOptions() {
        document.querySelectorAll('.product-select').forEach(sel => {
            const cur = sel.value;
            sel.innerHTML = '<option value="">Select a product...</option>' + productOptionsTemplate.innerHTML;
            Array.from(sel.options).forEach(opt => {
                if (opt.value && selectedProducts.has(opt.value) && opt.value !== cur) {
                    opt.disabled = true;
                    opt.innerHTML += ' (already selected)';
                }
            });
            if (cur) {
                sel.value = cur;
            }
        });
    }

    function updateSelectedProducts() {
        selectedProducts.clear();
        document.querySelectorAll('.product-select').forEach(s => {
            if (s.value && s.value !== '') {
                selectedProducts.add(s.value);
            }
        });
        updateProductOptions();
    }

    // Auto-fill Monthly Price when product is selected
    function autoFillMonthlyPrice(idx) {
        const sel = document.querySelector(`.product-select[data-index="${idx}"]`);
        const monthlyPriceInput = document.querySelector(`.monthly-price[data-index="${idx}"]`);
        
        const price = sel.selectedOptions[0]?.dataset.price || 0;
        
        if (price && price > 0) {
            monthlyPriceInput.value = parseFloat(price).toFixed(2);
        }
    }

    // Amount Calculation (monthly_price * billing_months)
    function calculateProductAmount(idx) {
        const monthlyPriceInput = document.querySelector(`.monthly-price[data-index="${idx}"]`);
        const months = document.querySelector(`.billing-months[data-index="${idx}"]`).value;
        const amtEl = document.querySelector(`.product-amount[data-index="${idx}"]`);
        const sumEl = document.getElementById(`productSummary${idx}`);

        const monthlyPrice = parseFloat(monthlyPriceInput.value) || 0;
        const total = monthlyPrice * months;

        productAmounts[idx] = total;
        amtEl.textContent = `৳ ${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        
        // Update summary with correct sequential number
        const productRows = Array.from(document.querySelectorAll('.product-row'));
        const rowIndex = productRows.findIndex(row => row.dataset.index == idx);
        const displayNumber = rowIndex + 1;
        
        if (sumEl) {
            sumEl.innerHTML = `<span>Product ${displayNumber}:</span><span>৳ ${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
        }
        
        calculateTotal();
    }

    function calculateTotal() {
        const tot = Object.values(productAmounts).reduce((a, b) => a + b, 0);
        document.getElementById('totalAmount').textContent = `৳ ${tot.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    }

    // Check for existing products function
    function checkExistingProducts(customerId, productId, index) {
        if (!customerId || !productId) return Promise.resolve(true);
        
        return fetch(`/admin/customer-to-products/check-existing?customer_id=${customerId}&product_id=${productId}`)
            .then(response => response.json())
            .then(data => {
                const productSelect = document.querySelector(`.product-select[data-index="${index}"]`);
                const productRow = productSelect.closest('.product-row');
                let warningElement = productRow.querySelector('.product-warning');
                
                if (data.exists) {
                    if (!warningElement) {
                        warningElement = document.createElement('div');
                        warningElement.className = 'product-warning alert alert-warning mt-2';
                        productRow.appendChild(warningElement);
                    }
                    warningElement.innerHTML = `
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        ${data.message}
                    `;
                    warningElement.style.display = 'block';
                    
                    // Add error styling to the select
                    productSelect.classList.add('is-invalid');
                    return false;
                } else {
                    if (warningElement) {
                        warningElement.style.display = 'none';
                    }
                    // Remove error styling
                    productSelect.classList.remove('is-invalid');
                    return true;
                }
            })
            .catch(error => {
                console.error('Error checking existing products:', error);
                return true;
            });
    }

    // Enhanced form validation with duplicate product checking
    document.getElementById('assignProductForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!customerIdInput.value) {
            alert('Please select a customer.');
            return;
        }
        
        const selects = document.querySelectorAll('.product-select');
        const filled = Array.from(selects).filter(s => s.value && s.value !== '');
        
        if (filled.length === 0) {
            alert('Please select at least one product.');
            return;
        }
        
        // Check for duplicates in current selection
        const selectedValues = filled.map(s => s.value);
        if (new Set(selectedValues).size !== selectedValues.length) {
            alert('You cannot assign the same product twice.');
            return;
        }
        
        // Check if assign dates are valid (not in the future)
        const today = new Date().toISOString().split('T')[0];
        const assignDateInputs = document.querySelectorAll('.assign-date');
        for (let input of assignDateInputs) {
            if (input.value && input.value > today) {
                alert('Assign date cannot be in the future. Please select today or an earlier date.');
                return;
            }
        }
        
        // Check for existing products with the customer
        const customerId = customerIdInput.value;
        const checkPromises = filled.map(select => {
            return checkExistingProducts(customerId, select.value, select.dataset.index);
        });
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Checking Products...';
        
        Promise.all(checkPromises).then(results => {
            const allValid = results.every(valid => valid);
            
            if (!allValid) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Assign Products';
                alert('Cannot assign products. Please check the warning messages and select different products.');
                return;
            }
            
            // If all valid, proceed with form submission
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Assigning Products...';
            
            // Submit the form using standard submit to ensure CSRF token is included
            document.getElementById('assignProductForm').submit();
        });
    });

    // Initial event listeners for first product
    const initialSelect = document.querySelector('.product-select[data-index="0"]');
    const initialMonthlyPrice = document.querySelector('.monthly-price[data-index="0"]');
    const initialMonths = document.querySelector('.billing-months[data-index="0"]');
    
    if (initialSelect) {
        initialSelect.addEventListener('change', function() {
            updateSelectedProducts();
            autoFillMonthlyPrice(0);
            calculateProductAmount(0);
            
            // Check for existing products
            const customerId = document.getElementById('customerId').value;
            if (customerId && this.value) {
                checkExistingProducts(customerId, this.value, 0);
            }
            
            updateSubmitButton();
        });
    }
    
    if (initialMonthlyPrice) {
        initialMonthlyPrice.addEventListener('input', function() {
            calculateProductAmount(0);
            updateSubmitButton();
        });
    }
    
    if (initialMonths) {
        initialMonths.addEventListener('change', function() {
            calculateProductAmount(0);
            updateSubmitButton();
        });
    }

    // Also listen for customer selection changes
    customerIdInput.addEventListener('change', updateSubmitButton);

    // Initial setup
    updateSelectedProducts();
    if (initialSelect && initialSelect.value) {
        autoFillMonthlyPrice(0);
        calculateProductAmount(0);
    }
    updateSubmitButton();
});

// Enhanced Customer Selection Function
function selectCustomer(id, name, phone, email, custId) {
    document.getElementById('customerId').value = id;
    document.getElementById('selectedCustomerName').textContent = name;
    
    let details = '';
    if (phone !== 'No phone') details += `Phone: ${phone} • `;
    details += `ID: ${custId}`;
    if (email !== 'No email') details += ` • Email: ${email}`;
    
    document.getElementById('selectedCustomerDetails').textContent = details;
    document.getElementById('selectedCustomerId').textContent = `Customer ID: ${custId}`;
    document.getElementById('customerSearch').value = name;
    document.getElementById('customerResults').style.display = 'none';
    document.getElementById('selectedCustomer').style.display = 'block';
    
    // Check all selected products for this customer
    document.querySelectorAll('.product-select').forEach(select => {
        const index = select.dataset.index;
        if (select.value) {
            checkExistingProducts(id, select.value, index);
        }
    });
    
    // Force update of submit button
    const event = new Event('change');
    document.getElementById('customerId').dispatchEvent(event);
}

function clearCustomerSelection() {
    document.getElementById('customerId').value = '';
    document.getElementById('selectedCustomer').style.display = 'none';
    document.getElementById('customerSearch').value = '';
    document.getElementById('customerResults').style.display = 'none';
    
    // Clear all product warnings
    document.querySelectorAll('.product-warning').forEach(warning => {
        warning.style.display = 'none';
    });
    
    // Remove error styling from all selects
    document.querySelectorAll('.product-select').forEach(select => {
        select.classList.remove('is-invalid');
    });
    
    // Force update of submit button
    const event = new Event('change');
    document.getElementById('customerId').dispatchEvent(event);
}
</script>
@endsection
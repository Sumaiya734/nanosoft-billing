@extends('layouts.admin')

@section('title', 'Monthly Bills - Admin Dashboard')

@section('content')
<!-- Toast Notification Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 10000; min-width: 350px;"></div>

<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 page-title">
                <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Monthly Bills - {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
            </h2>
            <p class="text-muted mb-0">Manage and view all customer bills for the selected month</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportMonthlyBills()">
                <i class="fas fa-download me-1"></i>Export Report
            </button>
            @if(!($isCurrentMonth ?? false))
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateBillsModal">
                <i class="fas fa-plus me-1"></i>Generate Bills
            </button>
            @endif
            <a href="{{ route('admin.billing.billing-invoices') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Billing
            </a>
        </div>
    </div>

    <!-- Billing Cycle Info -->
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Note:</strong> 
        <ul class="mb-0">
            <li>Customers with invoices are shown in the table below</li>
            <li>Customers who are due but don't have invoices yet are highlighted with a warning</li>
            @if(!($isCurrentMonth ?? false))
            <li>Use the "Generate Bills" button to create invoices for all customers or only those who are due</li>
            @else
            <li>Invoices for current month customers are automatically generated</li>
            @endif
        </ul>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Invoices</div>
                            <div class="h5 mb-0">{{ $totalCustomersWithInvoices ?? 0 }}</div>
                            @if(isset($customersWithDue) && isset($fullyPaidCustomers))
                            <div class="small">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $customersWithDue }} with due
                                <br>
                                <i class="fas fa-check-circle me-1"></i>{{ $fullyPaidCustomers }} fully paid
                            </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Billing Amount</div>
                            <div class="h5 mb-0">৳ {{ number_format($totalBillingAmount ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Payments</div>
                            <div class="h5 mb-0">৳ {{ number_format($pendingAmount ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Paid Amount</div>
                            <div class="h5 mb-0">৳ {{ number_format($paidAmount ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Bills Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Monthly Bills for {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                </h5>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" class="form-control" placeholder="Search..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <select class="form-select form-select-sm" style="width: 150px;" id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="paid">Paid</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="partial">Partial</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="monthlyBillsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice ID</th>
                            <th>Customer Info</th>
<<<<<<< HEAD
                            <th>Product</th>
                            <th>Subtotal</th>
=======
                            <th>products</th>
                            <th>Bill Amount</th>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                            <th>Previous Due</th>
                            <th>Total Amount</th>
                            <th>Received</th>
                            <th>Next Due</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices ?? [] as $invoice)
<<<<<<< HEAD
                            @php
                                $currentMonth = \Carbon\Carbon::parse($month . '-01');
                                // Invoice now directly links to one customer product
                                $customerProduct = $invoice->customerProduct;
                                
                                // Use EXACT data from invoices table columns
                                $invoiceSubtotal = $invoice->subtotal ?? 0;
                                $invoicePreviousDue = $invoice->previous_due ?? 0;
                                $invoiceTotalAmount = $invoice->total_amount ?? 0;
                                $invoiceReceivedAmount = $invoice->received_amount ?? 0;
                                $invoiceNextDue = $invoice->next_due ?? 0;
                                $invoiceStatus = $invoice->status ?? 'unpaid';
                                
                                $isFullyPaid = $invoiceNextDue < 0.01;
                                // Determine actual status
                                $actualStatus = $invoiceStatus;
                                if ($isFullyPaid) {
                                    $actualStatus = 'paid';
                                } elseif ($invoiceReceivedAmount > 0 && $invoiceNextDue > 0) {
                                    $actualStatus = 'partial';
                                } elseif ($invoiceReceivedAmount == 0) {
                                    $actualStatus = 'unpaid';
                                }
                                
                                // Get billing cycle from customer_to_products table
                                $billingCycle = $customerProduct->billing_cycle_months ?? 1;
                            @endphp

                            @if($customerProduct && $customerProduct->product)
                                <tr>
                                            {{-- Invoice ID --}}
                                            <td class="align-middle border-end">
                                                <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                                <br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M j, Y') }}</small>
                                            </td>

                                            {{-- Customer Info --}}
                                            <td class="align-middle border-end">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $invoice->customer->name ?? 'N/A' }}</h6>
                                                        <div class="text-muted small">
                                                            <div>{{ $invoice->customer->email ?? 'N/A' }}</div>
                                                            <div>{{ $invoice->customer->phone ?? 'N/A' }}</div>
                                                            <div class="mt-1">
                                                                <span class="badge bg-light text-dark">{{ $invoice->customer->customer_id ?? 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                           {{-- Single Product Info with Due Date --}}
                                            <td>
                                                <div class="fw-medium text-dark">{{ $customerProduct->product->name ?? 'Unknown Product' }}</div>
                                                <div class="text-muted small">
                                                    ৳ {{ number_format($customerProduct->product->monthly_price ?? 0, 2) }}/month
                                                </div>
                                                {{-- Billing Cycle - Stands Out --}}
                                                @if($billingCycle > 1)
                                                    <div class="mt-1">
                                                        <span class="badge bg-primary" style="font-size: 0.85rem; padding: 4px 10px;">
                                                            <i class="fas fa-sync-alt me-1"></i>{{ $billingCycle }} Months Billing Cycle
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="mt-1">
                                                        <span class="badge bg-secondary" style="font-size: 0.85rem; padding: 4px 10px;">
                                                            <i class="fas fa-calendar me-1"></i>Monthly
                                                        </span>
                                                    </div>
                                                @endif
                                                @php
                                                    // Simple calculation: due_date - assign_date = total_days
                                                    $assignDate = \Carbon\Carbon::parse($customerProduct->assign_date);
                                                    $invoiceMonth = \Carbon\Carbon::parse($month . '-01');
                                                    $today = \Carbon\Carbon::now();
                                                    
                                                    $actualDueDate = null;
                                                    $billingCycleMonths = 1;
                                                    
                                                    if ($customerProduct->due_date) {
                                                        $dueDate = \Carbon\Carbon::parse($customerProduct->due_date);
                                                        
                                                        // Calculate: due_date - assign_date = total_days
                                                        $totalDays = $assignDate->diffInDays($dueDate);
                                                        
                                                        // Calculate: total_days / 30 = total_months (billing cycle)
                                                        $billingCycleMonths = max(1, round($totalDays / 30));
                                                        
                                                        // Calculate: current_month = (invoice_month - assign_date) in months
                                                        $monthsSinceAssign = $assignDate->diffInMonths($invoiceMonth);
                                                        
                                                        // Check if this invoice month is a billing month
                                                        if ($monthsSinceAssign % $billingCycleMonths === 0) {
                                                            // Due date is on the same day as the original due_date
                                                            $dueDay = $dueDate->day;
                                                            $actualDueDate = $invoiceMonth->copy()->day(min($dueDay, $invoiceMonth->daysInMonth));
                                                        }
                                                    } else {
                                                        // If no due_date set, use assign_date day and billing_cycle_months from DB
                                                        $billingCycleMonths = $customerProduct->billing_cycle_months ?? 1;
                                                        $monthsSinceAssign = $assignDate->diffInMonths($invoiceMonth);
                                                        
                                                        if ($monthsSinceAssign % $billingCycleMonths === 0) {
                                                            $dueDay = $assignDate->day;
                                                            $actualDueDate = $invoiceMonth->copy()->day(min($dueDay, $invoiceMonth->daysInMonth));
                                                        }
                                                    }
                                                @endphp
                                                @if($actualDueDate)
                                                    <div class="mt-1">
                                                        <small class="text-success">
                                                            <i class="fas fa-calendar-check me-1"></i>
                                                            <strong>Due: {{ $actualDueDate->format('M j, Y') }}</strong>
                                                        </small>
                                                        @if($billingCycleMonths > 1)
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Every {{ $billingCycleMonths }} month(s)
                                                        </small>
                                                        @endif
                                                    </div>
                                                @elseif($customerProduct->next_billing_date)
                                                    <div class="mt-1">
                                                        <small class="text-warning">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            Next Due: {{ \Carbon\Carbon::parse($customerProduct->next_billing_date)->format('M j, Y') }}
                                                        </small>
                                                        <br>
                                                        <small class="text-muted">Not due this month</small>
                                                    </div>
                                                @else
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>No due date set
=======
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M j, Y') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $invoice->customer->name ?? 'N/A' }}</h6>
                                        <div class="text-muted small">
                                            <div>{{ $invoice->customer->email ?? 'N/A' }}</div>
                                            <div>{{ $invoice->customer->phone ?? 'N/A' }}</div>
                                            <div class="mt-1">
                                                <span class="badge bg-light text-dark">{{ $invoice->customer->customer_id ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="products-list">
                                    @php
                                        $customerproducts = $invoice->customer->customerproducts ?? collect();
                                    @endphp
                                    @if($customerproducts->count() > 0)
                                        @foreach($customerproducts as $customerproduct)
                                            @if($customerproduct->product)
                                            <div class="product-item mb-2">
                                                <div class="fw-medium text-dark">{{ $customerproduct->product->name ?? 'Unknown product' }}</div>
                                                <div class="text-muted small">
                                                    ৳ {{ number_format($customerproduct->product->monthly_price ?? 0, 2) }}/month
                                                    @if($customerproduct->billing_cycle_months > 1)
                                                    <span class="badge bg-info">({{ $customerproduct->billing_cycle_months }} months)</span>
                                                    @endif
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            Status: 
                                                            <span class="badge bg-{{ $customerproduct->status == 'active' ? 'success' : 'warning' }}">
                                                                {{ $customerproduct->status }}
                                                            </span>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Subtotal (from invoices table) --}}
                                            <td>
                                                <div class="bill-amount">
                                                    <strong class="text-dark">৳ {{ number_format($invoiceSubtotal, 2) }}</strong>
                                                    <br><small class="text-muted">Current charges</small>
                                                </div>
                                            </td>

                                            {{-- Previous Due (same for all product rows of this invoice) --}}
                                            <td>
                                                <div class="previous-due">
                                                    @if($invoicePreviousDue > 0)
                                                        <strong class="text-warning">৳ {{ number_format($invoicePreviousDue, 2) }}</strong>
                                                        <br><small class="text-muted">From past</small>
                                                    @else
                                                        <span class="text-success">৳ 0.00</span>
                                                        <br><small class="text-muted">No arrears</small>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Total Invoice (same across all rows) --}}
                                            <td>
                                                <div class="total-amount">
                                                    <strong class="text-success">৳ {{ number_format($invoiceTotalAmount, 2) }}</strong>
                                                    <br><small class="text-muted">Total due</small>
                                                </div>
                                            </td>

                                            {{-- Received Amount (same) --}}
                                            <td>
                                                <div class="received-amount">
                                                    @if($invoiceReceivedAmount > 0)
                                                        <strong class="text-info">৳ {{ number_format($invoiceReceivedAmount, 2) }}</strong>
                                                        @if($invoiceTotalAmount > 0)
                                                        <br><small class="text-muted">{{ number_format(($invoiceReceivedAmount / $invoiceTotalAmount) * 100, 1) }}% paid</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">৳ 0.00</span>
                                                        <br><small class="text-muted">No payment</small>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Next Due (same) --}}
                                            <td>
                                                <div class="next-due">
                                                    @if($isFullyPaid)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Paid
                                                        </span>
                                                        <br><small class="text-muted">Fully paid</small>
                                                    @else
                                                        <strong class="text-danger">৳ {{ number_format($invoiceNextDue, 2) }}</strong>
                                                        <br><small class="text-muted">Outstanding</small>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Status (same) --}}
                                            <td class="align-middle">
                                                @if($actualStatus == 'paid')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Paid
                                                    </span>
                                                @elseif($actualStatus == 'unpaid')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-clock me-1"></i>Unpaid
                                                    </span>
                                                @elseif($actualStatus == 'partial')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-hourglass-half me-1"></i>Partial
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-times me-1"></i>Cancelled
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Actions (same, but keep data consistent) --}}
                                            <td class="align-middle">
                                                <div class="d-flex flex-column gap-1">
                                                    @if(!$isFullyPaid)
                                                        <button class="btn btn-success btn-sm payment-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addPaymentModal"
                                                                data-invoice-id="{{ $invoice->invoice_id }}"
                                                                data-invoice-number="{{ $invoice->invoice_number }}"
                                                                data-customer-name="{{ e($invoice->customer->name ?? 'Customer') }}"
                                                                data-customer-email="{{ e($invoice->customer->email ?? '') }}"
                                                                data-customer-phone="{{ e($invoice->customer->phone ?? '') }}"
                                                                data-cp-id="{{ $customerProduct->cp_id }}"
                                                                data-product-name="{{ e($customerProduct->product->name ?? 'Unknown Product') }}"
                                                                data-product-price="{{ number_format($customerProduct->product->monthly_price ?? 0, 2, '.', '') }}"
                                                                data-billing-cycle="{{ $billingCycle }}"
                                                                data-product-amount="{{ number_format(($customerProduct->product->monthly_price ?? 0) * $billingCycle, 2, '.', '') }}"
                                                                data-subtotal="{{ number_format($invoiceSubtotal, 2, '.', '') }}"
                                                                data-previous-due="{{ number_format($invoicePreviousDue, 2, '.', '') }}"
                                                                data-total-amount="{{ number_format($invoiceTotalAmount, 2, '.', '') }}"
                                                                data-received-amount="{{ number_format($invoiceReceivedAmount, 2, '.', '') }}"
                                                                data-status="{{ $invoiceStatus }}"
                                                                title="Pay for {{ $customerProduct->product->name ?? 'this product' }}">
                                                            <i class="fas fa-money-bill-wave"></i> Pay Now
                                                        </button>
                                                    @else
                                                        <button class="btn btn-secondary btn-sm" disabled title="Invoice fully paid">
                                                            <i class="fas fa-money-bill-wave"></i> Pay Now
                                                        </button>
                                                    @endif

                                                    @if($invoiceReceivedAmount > 0)
                                                        @if(!$isFullyPaid)
                                                            <button class="btn btn-outline-warning btn-sm"
                                                                    onclick="editPayment({{ $invoice->invoice_id }})"
                                                                    title="Edit Payment">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        @else
                                                            <button class="btn btn-secondary btn-sm" disabled title="Payment confirmed">
                                                                <i class="fas fa-check-circle"></i> Confirmed
                                                            </button>
                                                        @endif
                                                    @endif

                                                    @if($isFullyPaid)
                                                        <button class="btn btn-outline-primary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewInvoiceModal"
                                                                onclick="viewInvoice({{ $invoice->invoice_id }})"
                                                                title="View Invoice">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                            @else
                                {{-- If no products, show one row with "No products" --}}
                                <tr>
                                    <td class="align-middle border-end">
                                        <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M j, Y') }}</small>
                                    </td>
                                    <td class="align-middle border-end">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $invoice->customer->name ?? 'N/A' }}</h6>
                                                <div class="text-muted small">
                                                    <div>{{ $invoice->customer->email ?? 'N/A' }}</div>
                                                    <div>{{ $invoice->customer->phone ?? 'N/A' }}</div>
                                                    <div class="mt-1">
                                                        <span class="badge bg-light text-dark">{{ $invoice->customer->customer_id ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
<<<<<<< HEAD
=======
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-muted">No products assigned</span>
                                    @endif
                                </div>
                            </td>
                            
                            <td>
                                <div class="bill-amount">
                                    <strong class="text-dark">৳ {{ number_format(($invoice->total_amount ?? 0) - ($invoice->previous_due ?? 0), 2) }}</strong>
                                    <div class="text-muted small">
                                        <div>Service: ৳ {{ number_format($invoice->service_charge ?? 0, 2) }}</div>
                                        @if(($invoice->vat_amount ?? 0) > 0)
                                        <div>VAT: ৳ {{ number_format($invoice->vat_amount ?? 0, 2) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="previous-due">
                                    @if(($invoice->previous_due ?? 0) > 0)
                                        <strong class="text-warning">৳ {{ number_format($invoice->previous_due ?? 0, 2) }}</strong>
                                        <div class="text-muted small">From previous bills</div>
                                    @else
                                        <span class="text-success">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="total-amount">
                                    <strong class="text-success">৳ {{ number_format($invoice->total_amount ?? 0, 2) }}</strong>
                                </div>
                            </td>
                            <td>
                                <div class="received-amount">
                                    @if(($invoice->received_amount ?? 0) > 0)
                                        <strong class="text-info">৳ {{ number_format($invoice->received_amount ?? 0, 2) }}</strong>
                                        @if(($invoice->total_amount ?? 0) > 0)
                                        <div class="text-muted small">
                                            {{ number_format((($invoice->received_amount ?? 0) / ($invoice->total_amount ?? 1)) * 100, 1) }}% paid
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                                        </div>
                                    </td>
                                    <td colspan="8" class="text-center text-muted">No products</td>
                                </tr>
                            @endif

<<<<<<< HEAD
=======
                                    {{-- Payment Button: Only for unpaid or partial invoices --}}
                                    @if(in_array($invoice->status, ['unpaid', 'partial']))
                                        <button class="btn btn-outline-success btn-sm payment-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addPaymentModal"
                                                data-invoice-id="{{ $invoice->invoice_id }}"
                                                data-invoice-number="{{ $invoice->invoice_number }}"
                                                data-customer-name="{{ e($invoice->customer->name ?? 'Customer') }}"
                                                data-customer-email="{{ e($invoice->customer->email ?? 'N/A') }}"
                                                data-customer-phone="{{ e($invoice->customer->phone ?? 'N/A') }}"
                                                data-total-amount="{{ $invoice->total_amount ?? 0 }}"
                                                data-due-amount="{{ $invoice->next_due ?? 0 }}"
                                                data-received-amount="{{ $invoice->received_amount ?? 0 }}"
                                                data-status="{{ $invoice->status }}"
                                                title="Add Payment">
                                            <i class="fas fa-money-bill-wave"></i> Payment
                                        </button>
                                    @else
                                        <button class="btn btn-outline-success btn-sm" disabled title="Invoice already paid">
                                            <i class="fas fa-check me-1"></i> Paid
                                        </button>
                                    @endif

                                    {{-- Reminder Button: Only for unpaid or partial invoices --}}
                                    @if(in_array($invoice->status, ['unpaid', 'partial']))
                                        <button class="btn btn-outline-info btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#sendReminderModal"
                                                data-invoice-id="{{ $invoice->invoice_id }}"
                                                data-customer-name="{{ e($invoice->customer->name ?? 'Customer') }}"
                                                data-customer-email="{{ e($invoice->customer->email ?? 'N/A') }}"
                                                title="Send Payment Reminder">
                                            <i class="fas fa-bell"></i> Reminder
                                        </button>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled title="No reminder needed">
                                            <i class="fas fa-bell-slash me-1"></i> No Reminder
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                        <h5>No bills found for {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</h5>
                                        <p>Generate bills for this month to get started.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateBillsModal">
                                            <i class="fas fa-plus me-1"></i>Generate Bills
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Due customers without invoices (unchanged) --}}
                        @if(isset($dueCustomers) && $dueCustomers->isNotEmpty())
                            @php
                                $invoiceCustomerIds = ($invoices ?? collect())->pluck('c_id')->toArray();
                            @endphp
                            @foreach($dueCustomers as $dueCustomer)
                                @if(!in_array($dueCustomer->c_id, $invoiceCustomerIds))
                                    <tr class="table-warning">
                                        <td class="align-middle border-end">
                                            <strong class="text-muted">Not Generated</strong>
                                            <br>
                                            <small class="text-muted">No invoice yet</small>
                                        </td>
                                        <td class="align-middle border-end">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $dueCustomer->name ?? 'N/A' }}</h6>
                                                    <div class="text-muted small">
                                                        <div>{{ $dueCustomer->email ?? 'N/A' }}</div>
                                                        <div>{{ $dueCustomer->phone ?? 'N/A' }}</div>
                                                        <div class="mt-1">
                                                            <span class="badge bg-light text-dark">{{ $dueCustomer->customer_id ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="8" class="text-center">
                                            <div class="alert alert-warning mb-0">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Customer is due for billing this month but no invoice has been generated yet.</strong>
                                                <br>
                                                <small>Click "Generate Bills" button above to create invoices for all due customers.</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="row align-items-center">
                <div class="col-md-12 mb-2">
                    <div class="alert alert-info mb-0 small">
                        <strong><i class="fas fa-info-circle me-1"></i>How to read this table:</strong>
                        <ul class="mb-0 mt-1">
                            <li><strong>Product Amount</strong> = Current month charges (from products)</li>
                            <li><strong>Previous Due</strong> = Unpaid balance from past months</li>
                            <li><strong>Total Invoice</strong> = Product Amount + Previous Due</li>
                            <li><strong>Received</strong> = Payments made against this invoice</li>
                            <li><strong>Next Due</strong> = Total Invoice - Received (what customer still owes)</li>
                        </ul>
                        <div class="mt-2 p-2 bg-light rounded">
                            <strong><i class="fas fa-calculator me-1"></i>Verification:</strong> 
                            <div class="mt-1">
                                Total Billing (৳{{ number_format($totalBillingAmount ?? 0, 2) }}) 
                                - Paid (৳{{ number_format($paidAmount ?? 0, 2) }}) 
                                = Pending (৳{{ number_format($pendingAmount ?? 0, 2) }})
                            </div>
                            @php
                                $calculatedPending = ($totalBillingAmount ?? 0) - ($paidAmount ?? 0);
                                $isBalanced = abs($calculatedPending - ($pendingAmount ?? 0)) < 0.01;
                            @endphp
                            <div class="mt-1">
                                <span class="badge {{ $isBalanced ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas fa-{{ $isBalanced ? 'check' : 'exclamation-triangle' }} me-1"></i>
                                    {{ $isBalanced ? 'Balanced ✓' : 'Mismatch!' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Showing {{ ($invoices ?? collect())->count() }} invoices for {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Last updated: {{ now()->format('M j, Y g:i A') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Bills Modal -->
<div class="modal fade" id="generateBillsModal" tabindex="-1" aria-labelledby="generateBillsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Monthly Bills</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
<<<<<<< HEAD
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Choose how you want to generate bills for {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                </div>
                
                <form id="generateBillsForm">
                    <input type="hidden" name="month" value="{{ $month }}">
=======
            <form action="{{ route('admin.billing.generate-monthly-bills') }}" method="POST">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This will generate bills for all active customers with products in {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                    </div>
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                    <div class="mb-3">
                        <label class="form-label">Billing Month</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}" readonly>
                    </div>
<<<<<<< HEAD
                    
                    <div class="mb-4">
                        <label class="form-label">Generation Options</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="generationType" id="dueOnly" value="due_only" checked>
                            <label class="form-check-label" for="dueOnly">
                                <strong>Due Customers Only</strong>
                                <div class="text-muted small">Generate bills only for customers who are due based on their billing cycle</div>
                            </label>
                        </div>
=======
                    <div class="mb-3">
                        <label class="form-label">Active Customers with products</label>
                        <input type="text" class="form-control" value="{{ $totalCustomers ?? 0 }} customers" readonly>
                    </div>
                    <div class="mb-3">
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="generationType" id="allCustomers" value="all_customers">
                            <label class="form-check-label" for="allCustomers">
                                <strong>All Active Customers</strong>
                                <div class="text-muted small">Generate bills for all active customers with products (regardless of billing cycle)</div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Active Customers with products</label>
                        <input type="text" class="form-control" value="{{ $totalCustomers ?? 0 }} customers" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="generateBills()">
                    <i class="fas fa-sync me-1"></i>Generate Bills
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Invoice Modal -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewInvoiceContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- Include Separate Payment Modal -->
@include('admin.billing.payment-modal')
@endsection

@section('styles')
<style>
    :root {
        --primary: #4361ee;
        --success: #06d6a0;
        --warning: #ffd166;
        --danger: #ef476f;
        --info: #118ab2;
        --dark: #2b2d42;
        --light: #f8f9fa;
    }

    body {
        background-color: #f5f7fb;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #eaeaea;
        border-radius: 12px 12px 0 0 !important;
        padding: 20px 25px;
    }

    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaeaea;
        padding: 12px 8px;
        background-color: #f8f9fa;
    }

    .table td {
        padding: 16px 8px;
        font-size: 0.9rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .badge-paid {
        background-color: var(--success);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-pending {
        background-color: var(--warning);
        color: black;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-partial {
        background-color: var(--info);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

<<<<<<< HEAD
    /* Enhanced badge styles for better visibility */
    .badge.bg-success {
        background-color: #06d6a0 !important;
        color: white !important;
        padding: 6px 12px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(6, 214, 160, 0.3);
    }

    .badge.bg-danger {
        background-color: #ef476f !important;
        color: white !important;
        padding: 6px 12px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(239, 71, 111, 0.3);
    }

    .badge.bg-warning {
        background-color: #ffd166 !important;
        color: #000 !important;
        padding: 6px 12px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(255, 209, 102, 0.3);
    }

    /* Paid button styling */
    .btn-success:disabled {
        background-color: #06d6a0 !important;
        border-color: #06d6a0 !important;
        opacity: 0.8;
    }

=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
    .products-list .product-item {
        padding: 8px;
        border-left: 3px solid var(--primary);
        background-color: #f8f9fa;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .products-list .product-item:last-child {
        margin-bottom: 0;
    }

    /* Product row grouping styles */
    tbody tr.border-top-0 {
        border-top: 1px dashed #e0e0e0 !important;
    }

    tbody td[rowspan] {
        background-color: #fafbfc;
        border-right: 2px solid #e9ecef;
    }

    tbody tr:hover td[rowspan] {
        background-color: rgba(67, 97, 238, 0.03);
    }

    .btn-sm {
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* Toast Notification Styles */
    #toastContainer {
        pointer-events: none;
    }

    #toastContainer .toast-notification {
        pointer-events: all;
    }

    .toast-notification .btn-close {
        opacity: 0.8;
    }

    .toast-notification .btn-close:hover {
        opacity: 1;
    }
</style>
@endsection

@section('scripts')
@vite(['resources/js/app.js'])

<script>
document.addEventListener('DOMContentLoaded', function () {
    const $modal = $('#addPaymentModal');

    // --------------------------------------------------------------
    // 1. MODAL OPEN → Fill data from button (even if click on <i>)
    // --------------------------------------------------------------
  $modal.on('show.bs.modal', function (e) {
    let $btn = $(e.relatedTarget);
    if (!$btn.hasClass('btn')) {
        $btn = $btn.closest('[data-invoice-id]');
    }

    if (!$btn.length) {
        showToast('Could not identify invoice. Please try again.', 'danger');
        return $modal.modal('hide');
    }

    const invoiceId = $btn.data('invoice-id');
    if (!invoiceId || isNaN(invoiceId)) {
        showToast('Invalid invoice ID.', 'danger');
        return $modal.modal('hide');
    }

    // Get data from button attributes (product-specific)
    const invoiceNumber = $btn.data('invoice-number') || '–';
    const customerName = $btn.data('customer-name') || 'Unknown Customer';
    const customerEmail = $btn.data('customer-email') || '';
    const customerPhone = $btn.data('customer-phone') || '';
    
    // Invoice financial data
    const subtotal = parseFloat($btn.data('subtotal')) || 0;
    const previousDue = parseFloat($btn.data('previous-due')) || 0;
    const totalAmount = parseFloat($btn.data('total-amount')) || 0;
    const receivedAmount = parseFloat($btn.data('received-amount')) || 0;
    const nextDue = totalAmount - receivedAmount;
    
    // Product-specific data
    const cpId = $btn.data('cp-id');
    const productName = $btn.data('product-name') || 'Unknown Product';
    const productPrice = parseFloat($btn.data('product-price')) || 0;
    const billingCycle = parseInt($btn.data('billing-cycle')) || 1;
    const productAmount = parseFloat($btn.data('product-amount')) || 0;

    // Set form action, invoice ID and product ID
    $('#payment_invoice_id').val(invoiceId);
    $('#payment_cp_id').val(cpId);
    const recordPaymentUrl = "{{ url('admin/billing/record-payment') }}/" + invoiceId;
    $('#addPaymentForm').attr('action', recordPaymentUrl);

    // Update invoice and customer info
    $('#payment_invoice_number_display').text(invoiceNumber);
    $('#payment_customer_name_display').text(customerName);
    $('#payment_customer_email_display').text(customerEmail || 'N/A');
    $('#payment_customer_phone_display').text(customerPhone || 'N/A');
    
    // Update financial fields
    $('#payment_subtotal_display').text(`৳ ${subtotal.toLocaleString('en-BD', { minimumFractionDigits: 2 })}`);
    $('#payment_previous_due_display').text(`৳ ${previousDue.toLocaleString('en-BD', { minimumFractionDigits: 2 })}`);
    $('#payment_total_amount_display').text(`৳ ${totalAmount.toLocaleString('en-BD', { minimumFractionDigits: 2 })}`);
    $('#payment_max_amount').text(`৳ ${nextDue.toLocaleString('en-BD', { minimumFractionDigits: 2 })}`);
    $('#next_due').val(nextDue.toFixed(2));
    
    // Update product-specific fields
    $('#payment_product_name').text(productName);
    $('#payment_product_price').text(`৳ ${productPrice.toLocaleString('en-BD', { minimumFractionDigits: 2 })}`);
    $('#payment_billing_cycle').text(billingCycle > 1 ? `${billingCycle} months` : 'Monthly');
    $('#payment_product_amount').text(`৳ ${productAmount.toLocaleString('en-BD', { minimumFractionDigits: 2 })}`);

    // Configure amount input - max is next_due (remaining amount)
    const $amountInput = $('#payment_amount');
    $amountInput
        .val('')
        .attr({
            'min': '0.01',
            'max': nextDue,
            'step': '0.01'
        })
        .prop('disabled', nextDue <= 0)
        .removeClass('is-invalid');

    if (nextDue <= 0) {
        $amountInput.attr('placeholder', 'Invoice fully paid');
    } else {
        $amountInput.attr('placeholder', `Enter amount (Max: ৳${nextDue.toFixed(2)})`);
    }

    $('#payment_amount_error').hide();
});

    // --------------------------------------------------------------
    // 2. VALIDATE PAYMENT AMOUNT AND AUTO-CALCULATE NEXT DUE
    // --------------------------------------------------------------
    $('#payment_amount').on('input', function () {
        const paid = parseFloat(this.value) || 0;
        const totalAmountText = $('#payment_total_amount_display').text();
        const totalAmount = parseFloat(totalAmountText.replace(/[^\d.]/g, '')) || 0;
        const receivedAmount = parseFloat($('#addPaymentModal').data('received-amount')) || 0;
        const currentNextDue = totalAmount - receivedAmount;
        
        // Calculate new next_due after this payment
        const newNextDue = Math.max(0, currentNextDue - paid);
        $('#next_due').val(newNextDue.toFixed(2));

        if (paid > currentNextDue) {
            $(this).addClass('is-invalid');
            $('#payment_amount_error').show();
        } else {
            $(this).removeClass('is-invalid');
            $('#payment_amount_error').hide();
        }
    });

    // --------------------------------------------------------------
    // 3. SUBMIT PAYMENT VIA AJAX
    // --------------------------------------------------------------
    $('#addPaymentForm').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const oldHtml = $btn.html();

        const paid = parseFloat($('#payment_amount').val()) || 0;
        const totalAmountText = $('#payment_total_amount_display').text();
        const totalAmount = parseFloat(totalAmountText.replace(/[^\d.]/g, '')) || 0;
        const receivedAmount = parseFloat($('#addPaymentModal').data('received-amount')) || 0;
        const currentNextDue = totalAmount - receivedAmount;

        if (paid <= 0) {
            showToast('Amount must be greater than 0!', 'danger');
            return;
        }
        if (paid > currentNextDue) {
            showToast('Cannot pay more than next due amount!', 'danger');
            return;
        }

        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...').prop('disabled', true);

        fetch($form.attr('action'), {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(r => r.json())
        .then(json => {
            if (json.success) {
                const invoiceNumber = $('#payment_invoice_number_display').text();
                const customerName = $('#payment_customer_name_display').text();
                const paidAmount = parseFloat($('#payment_amount').val()) || 0;
                
                // Show detailed success notification
                const details = `
                    <div><strong>Invoice:</strong> ${invoiceNumber}</div>
                    <div><strong>Customer:</strong> ${customerName}</div>
                    <div><strong>Amount Paid:</strong> ৳${paidAmount.toLocaleString('en-BD', { minimumFractionDigits: 2 })}</div>
                    <div class="mt-1"><small><i class="fas fa-sync me-1"></i>Refreshing page...</small></div>
                `;
                
                showToast('Payment Recorded Successfully!', 'success', details);
                
                $modal.modal('hide');
                
                // Show loading overlay
                $('body').append('<div class="loading-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;"><div class="spinner-border text-light" style="width:3rem;height:3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                
                // Reload page to show updated status
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(json.message || 'Error saving payment.', 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Network error. Try again.', 'danger');
        })
        .finally(() => {
            $btn.html(oldHtml).prop('disabled', false);
        });
    });

    // --------------------------------------------------------------
    // 4. RESET MODAL ON CLOSE
    // --------------------------------------------------------------
    $modal.on('hidden.bs.modal', function () {
        $('#addPaymentForm')[0].reset();
        $('#addPaymentForm').attr('action', '');
        $('#payment_invoice_id').val('');
        $('#payment_cp_id').val('');
        $('#payment_invoice_number_display').text('-');
        $('#payment_customer_name_display').text('-');
        $('#payment_customer_email_display').text('-');
        $('#payment_customer_phone_display').text('-');
        $('#payment_subtotal_display').text('৳ 0.00');
        $('#payment_previous_due_display').text('৳ 0.00');
        $('#payment_total_amount_display').text('৳ 0.00');
        $('#payment_max_amount').text('৳ 0.00');
        $('#next_due').val('0.00');
        $('#payment_product_name').text('-');
        $('#payment_product_price').text('৳ 0.00');
        $('#payment_billing_cycle').text('-');
        $('#payment_product_amount').text('৳ 0.00');
        $('#payment_amount').removeClass('is-invalid');
        $('#payment_amount_error').hide();
    });

    // --------------------------------------------------------------
    // 5. ENHANCED TOAST NOTIFICATION
    // --------------------------------------------------------------
    function showToast(msg, type = 'info', details = null) {
        const toastId = 'toast-' + Date.now();
        const icon = type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-circle' : 'info-circle';
        const bgColor = type === 'success' ? '#06d6a0' : type === 'danger' ? '#ef476f' : type === 'warning' ? '#ffd166' : '#118ab2';
        
        let detailsHtml = '';
        if (details) {
            detailsHtml = `<div class="mt-2 pt-2 border-top border-light" style="font-size: 0.85rem;">
                ${details}
            </div>`;
        }
        
        const toastHtml = `
            <div id="${toastId}" class="toast-notification" style="
                background: ${bgColor};
                color: white;
                padding: 16px 20px;
                border-radius: 12px;
                margin-bottom: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                animation: slideInRight 0.3s ease-out;
                max-width: 400px;
            ">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${icon} fa-lg me-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight: 600; margin-bottom: 4px;">${msg}</div>
                        ${detailsHtml}
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-3" onclick="document.getElementById('${toastId}').remove()" style="font-size: 0.8rem;"></button>
                </div>
            </div>
        `;
        
        $('#toastContainer').append(toastHtml);
        
        // Auto remove after 6 seconds
        setTimeout(() => {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }
        }, 6000);
    }
    
    // Add CSS animation
    if (!document.getElementById('toast-animations')) {
        const style = document.createElement('style');
        style.id = 'toast-animations';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // printInvoiceAfterPayment function removed as per user request

    // ------------------------------
    // OPTIONAL: Placeholder for export & view functions
    // ------------------------------
    window.exportMonthlyBills = function() {
        // Implement export logic (e.g., CSV/Excel via AJAX or redirect)
        alert('Export feature coming soon!');
    };

    window.viewInvoice = function(invoiceId) {
        const baseUrl = "{{ url('admin/billing/invoice') }}";
        const viewInvoiceUrl = baseUrl + '/' + invoiceId + '/html';
        const contentDiv = document.getElementById('viewInvoiceContent');
        
        // Show loading
        contentDiv.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3">Loading invoice...</p></div>';
        
        fetch(viewInvoiceUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load invoice (Status: ' + response.status + ')');
            }
            return response.text();
        })
        .then(html => {
            if (html && html.trim().length > 0) {
                contentDiv.innerHTML = html;
            } else {
                contentDiv.innerHTML = '<div class="alert alert-warning text-center py-4"><i class="fas fa-info-circle fa-2x mb-3"></i><h5>No invoice data</h5><p>The invoice appears to be empty.</p></div>';
            }
        })
        .catch(error => {
            console.error('Error loading invoice:', error);
            contentDiv.innerHTML = '<div class="alert alert-danger text-center py-4"><i class="fas fa-exclamation-triangle fa-2x mb-3"></i><h5>Failed to load invoice</h5><p>' + error.message + '</p><p class="small text-muted">Invoice ID: ' + invoiceId + '</p></div>';
        });
    };

    window.editPayment = function(invoiceId) {
        // Redirect to payment history or edit page
        const editUrl = "{{ url('admin/billing/invoice') }}/" + invoiceId + "/payments";
        window.location.href = editUrl;
    };

    // window.printInvoice function removed as per user request

    // Add this new function for dynamic status filtering
    function filterTableByStatus() {
        const statusFilter = document.getElementById('statusFilter').value;
        const tableRows = document.querySelectorAll('#monthlyBillsTable tbody tr');
        
        tableRows.forEach(row => {
            // Skip rows that are part of a group (they don't have status badges)
            const statusCell = row.cells[8]; // Status column
            if (!statusCell) return;
            
            const statusBadge = statusCell.querySelector('span');
            if (!statusBadge) return;
            
            // Get the status text from the badge
            const statusText = statusBadge.textContent.trim().toLowerCase();
            
            // Show/hide based on filter
            if (statusFilter === 'all' || 
                (statusFilter === 'paid' && statusText.includes('paid')) ||
                (statusFilter === 'unpaid' && statusText.includes('unpaid')) ||
                (statusFilter === 'partial' && statusText.includes('partial'))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Add event listener for status filter
    document.getElementById('statusFilter').addEventListener('change', filterTableByStatus);

    // Initialize filtering on page load
    document.addEventListener('DOMContentLoaded', function() {
        filterTableByStatus();
    });
});

// Add this new function for generating bills - moved outside to make it globally accessible
function generateBills() {
    // Get form elements with proper error checking
    const form = document.getElementById('generateBillsForm');
    if (!form) {
        console.error('Generate bills form not found');
        alert('Error: Form not found');
        return;
    }
    
    const monthInput = form.querySelector('input[name="month"]');
    if (!monthInput) {
        console.error('Month input not found');
        alert('Error: Month input not found');
        return;
    }
    const month = monthInput.value;
    
    const generationTypeInput = form.querySelector('input[name="generationType"]:checked');
    if (!generationTypeInput) {
        console.error('Generation type input not found');
        alert('Error: Please select a generation option');
        return;
    }
    const generationType = generationTypeInput.value;
    
    let url, message;
    if (generationType === 'all_customers') {
        url = "{{ route('admin.billing.generate-monthly-bills-all') }}";
        message = "Generating bills for all active customers...";
    } else {
        url = "{{ route('admin.billing.generate-monthly-bills') }}";
        message = "Generating bills for due customers only...";
    }
    
    // Show loading message
    const modal = document.getElementById('generateBillsModal');
    if (!modal) {
        console.error('Generate bills modal not found');
        alert('Error: Modal not found');
        return;
    }
    
    const originalContent = modal.querySelector('.modal-body').innerHTML;
    modal.querySelector('.modal-body').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">${message}</p>
        </div>
    `;
    
    // Disable buttons
    modal.querySelectorAll('button').forEach(btn => {
        btn.disabled = true;
    });
    
    // Submit form via fetch
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: `month=${encodeURIComponent(month)}`
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.json();
        }
    })
    .then(data => {
        // Close modal and refresh page
        const generateBillsModal = bootstrap.Modal.getInstance(document.getElementById('generateBillsModal'));
        if (generateBillsModal) {
            generateBillsModal.hide();
        }
        
        // Show success message and reload page
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to generate bills'));
            // Restore modal content and enable buttons
            modal.querySelector('.modal-body').innerHTML = originalContent;
            modal.querySelectorAll('button').forEach(btn => {
                btn.disabled = false;
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Restore modal content and enable buttons
        modal.querySelector('.modal-body').innerHTML = originalContent;
        modal.querySelectorAll('button').forEach(btn => {
            btn.disabled = false;
        });
        alert('Error generating bills: ' + error.message);
    });
}
</script>
@endsection
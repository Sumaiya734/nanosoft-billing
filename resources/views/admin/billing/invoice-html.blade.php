<div class="invoice-container p-4">
    <div class="invoice-header text-center mb-4">
        <h2 class="mb-1">Nanosoft Billing- Invoice</h2>
        <p class="text-muted mb-0">Invoice #{{ $invoice->invoice_number }}</p>
        <p class="text-muted small">Issue Date: {{ \Carbon\Carbon::parse($invoice->issue_date)->format('F j, Y') }}</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-uppercase text-muted mb-2">Bill To:</h6>
            <h5 class="mb-1">{{ $invoice->customer->name ?? 'N/A' }}</h5>
            <p class="mb-0 small">
                <strong>Customer ID:</strong> {{ $invoice->customer->customer_id ?? 'N/A' }}<br>
                <strong>Email:</strong> {{ $invoice->customer->email ?? 'N/A' }}<br>
                <strong>Phone:</strong> {{ $invoice->customer->phone ?? 'N/A' }}<br>
                <strong>Address:</strong> {{ $invoice->customer->address ?? 'N/A' }}
            </p>
        </div>
        <div class="col-md-6 text-end">
            <h6 class="text-uppercase text-muted mb-2">Invoice Details:</h6>
            <p class="mb-0 small">
                <strong>Invoice Number:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M j, Y') }}<br>
                <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M j, Y') }}<br>
                <strong>Status:</strong> 
                <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'partial' ? 'warning' : 'danger') }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </p>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Product/Service</th>
                    <th class="text-center">Billing Cycle</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dueProducts as $product)
                    @php
                        $monthlyPrice = $product->product->monthly_price ?? 0;
                        $billingCycle = $product->billing_cycle_months ?? 1;
                        $amount = $monthlyPrice * $billingCycle;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $product->product->name ?? 'Unknown Product' }}</strong>
                            <br>
                            <small class="text-muted">
                                Due Day: {{ $product->due_day ?? 'N/A' }}{{ $product->due_day == 1 ? 'st' : ($product->due_day == 2 ? 'nd' : ($product->due_day == 3 ? 'rd' : 'th')) }} of month
                            </small>
                        </td>
                        <td class="text-center">{{ $billingCycle }} Month{{ $billingCycle > 1 ? 's' : '' }}</td>
                        <td class="text-end">৳ {{ number_format($monthlyPrice, 2) }}</td>
                        <td class="text-end">৳ {{ number_format($amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No products found for this invoice</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                @if(($invoice->previous_due ?? 0) > 0)
                <tr>
                    <td colspan="3" class="text-end"><strong>Previous Due:</strong></td>
                    <td class="text-end text-warning"><strong>৳ {{ number_format($invoice->previous_due, 2) }}</strong></td>
                </tr>
                @endif
                <tr class="table-light">
                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                    <td class="text-end"><strong>৳ {{ number_format($invoice->total_amount ?? 0, 2) }}</strong></td>
                </tr>
                @if(($invoice->received_amount ?? 0) > 0)
                <tr>
                    <td colspan="3" class="text-end"><strong>Received Amount:</strong></td>
                    <td class="text-end text-success"><strong>৳ {{ number_format($invoice->received_amount, 2) }}</strong></td>
                </tr>
                @endif
                @if(($invoice->next_due ?? 0) > 0)
                <tr class="table-warning">
                    <td colspan="3" class="text-end"><strong>Amount Due:</strong></td>
                    <td class="text-end text-danger"><strong>৳ {{ number_format($invoice->next_due, 2) }}</strong></td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>

    @if($invoice->payments && $invoice->payments->count() > 0)
    <div class="payment-history mb-4">
        <h6 class="text-uppercase text-muted mb-3">Payment History</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') }}</td>
                        <td>৳ {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                        <td>{{ $payment->note ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="invoice-footer text-center mt-5 pt-4 border-top">
        <p class="text-muted small mb-0">Thank you for your business!</p>
        <p class="text-muted small">For any queries, please contact us.</p>
    </div>
</div>

<style>
    .invoice-container {
        background: white;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .invoice-header h2 {
        color: #2c3e50;
        font-weight: 700;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .table-bordered {
        border: 2px solid #dee2e6;
    }
    
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
    
    @media print {
        .invoice-container {
            padding: 20px;
        }
        
        .btn, .modal-footer {
            display: none !important;
        }
    }
</style>

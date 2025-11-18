<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\CustomerProduct;
use App\Models\Payment;
<<<<<<< HEAD
=======
use App\Models\product;
use App\Models\Customerproduct;
use Carbon\Carbon;
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770

class MonthlyBillController extends Controller
{
    /**
     * Display monthly bills for a specific month
     */
    public function monthlyBills($month)
    {
        try {
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            $displayMonth = $monthDate->format('F Y');
            
            // Check if it's a future month
            $currentMonth = Carbon::now()->format('Y-m');
            $isFutureMonth = $month > $currentMonth;
            
            // Check if it's the current month
            $isCurrentMonth = $month === $currentMonth;

            // Get invoices for the selected month with relationships
            $invoices = Invoice::with([
                'customer', 
                'payments',
                'customer.customerproducts.product'
            ])
            ->whereYear('issue_date', $monthDate->year)
            ->whereMonth('issue_date', $monthDate->month)
            ->orderBy('issue_date', 'desc')
            ->orderBy('invoice_id', 'desc')
            ->get();

            // Get customers who are due for billing in this month (even if no invoice exists yet)
            $dueCustomers = $this->getDueCustomersForMonth($monthDate);
            
            // Get ALL active customers with products for current month auto-generation
            $allActiveCustomers = $this->getAllActiveCustomersWithProducts($monthDate);
            
            // Automatically generate invoices for ALL active customers if it's the current month and some invoices are missing
            if ($isCurrentMonth && !$isFutureMonth && $allActiveCustomers->count() > $invoices->count()) {
                // Only generate invoices if there are more active customers than existing invoices
                $this->autoGenerateMissingInvoicesForAll($monthDate, $allActiveCustomers, $invoices);
                
                // Refresh invoices after auto-generation
                $invoices = Invoice::with([
                    'customer', 
                    'payments',
                    'customer.customerproducts.product'
                ])
                ->whereYear('issue_date', $monthDate->year)
                ->whereMonth('issue_date', $monthDate->month)
                ->orderBy('issue_date', 'desc')
                ->orderBy('invoice_id', 'desc')
                ->get();
                
                // Refresh due customers after auto-generation
                $dueCustomers = $this->getDueCustomersForMonth($monthDate);
            }
            
            // Calculate statistics based on actual invoices
            $totalCustomersWithInvoices = $invoices->count();
            
            // Calculate customers with outstanding payments (unpaid + partial)
            $customersWithDue = $invoices->filter(function($invoice) {
                return $invoice->next_due > 0.01; // Has outstanding balance
            })->count();
            
            // Calculate fully paid customers
            $fullyPaidCustomers = $invoices->filter(function($invoice) {
                return $invoice->next_due < 0.01; // Fully paid
            })->count();
            
            // Update total customers to include due customers without invoices
            $totalDueCustomers = $dueCustomers->count();
            
            // Total customers is customers with outstanding dues
            $totalCustomers = $customersWithDue;
            
            $totalBillingAmount = $invoices->sum('total_amount');
            $paidAmount = $invoices->sum('received_amount');
            $pendingAmount = $invoices->sum('next_due');

            // Get available months for the dropdown
            $availableMonths = $this->getAvailableBillingMonths();

            // Get system settings for service charge and VAT
            $systemSettings = $this->getSystemSettings();

            return view('admin.billing.monthly-bills', [
                'month' => $month,
                'displayMonth' => $displayMonth,
                'invoices' => $invoices,
                'dueCustomers' => $dueCustomers, // Add due customers to the view
                'totalCustomers' => $totalCustomers, // Customers with outstanding payments
                'totalCustomersWithInvoices' => $totalCustomersWithInvoices,
                'customersWithDue' => $customersWithDue, // Customers with outstanding balance
                'fullyPaidCustomers' => $fullyPaidCustomers, // Customers who paid fully
                'totalDueCustomers' => $totalDueCustomers,
                'totalBillingAmount' => $totalBillingAmount,
                'paidAmount' => $paidAmount,
                'pendingAmount' => $pendingAmount,
                'isFutureMonth' => $isFutureMonth,
                'isCurrentMonth' => $isCurrentMonth, // Add this to the view
                'availableMonths' => $availableMonths,
                'systemSettings' => $systemSettings
            ]);

        } catch (\Exception $e) {
            Log::error('Monthly bills error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading monthly bills: ' . $e->getMessage());
        }
    }

    /**
     * Automatically generate missing invoices for due customers
     */
    private function autoGenerateMissingInvoices(Carbon $monthDate, $dueCustomers, $existingInvoices)
    {
        try {
            // Get system settings
            $systemSettings = $this->getSystemSettings();
            $serviceCharge = $systemSettings['fixed_monthly_charge'] ?? 50.00;
            $vatPercentage = $systemSettings['vat_percentage'] ?? 5.00;
            
            // Get existing invoice customer IDs
            $existingCustomerIds = $existingInvoices->pluck('c_id')->toArray();
            
            // Generate invoices for due customers who don't have invoices yet
            $generatedCount = 0;
            foreach ($dueCustomers as $customer) {
                // Skip if invoice already exists
                if (in_array($customer->c_id, $existingCustomerIds)) {
                    continue;
                }
                
                try {
                    // Create new invoice
                    $this->createCustomerMonthlyInvoice($customer, $monthDate, $serviceCharge, $vatPercentage);
                    $generatedCount++;
                } catch (\Exception $e) {
                    Log::error("Auto-generation failed for customer {$customer->c_id}: " . $e->getMessage());
                }
            }
            
            if ($generatedCount > 0) {
                Log::info("Auto-generated {$generatedCount} invoices for {$monthDate->format('F Y')}");
            }
            
        } catch (\Exception $e) {
            Log::error('Auto-generate missing invoices error: ' . $e->getMessage());
        }
    }

    /**
     * Automatically generate missing invoices for ALL active customers
     */
    private function autoGenerateMissingInvoicesForAll(Carbon $monthDate, $allActiveCustomers, $existingInvoices)
    {
        try {
            // Get system settings
            $systemSettings = $this->getSystemSettings();
            $serviceCharge = $systemSettings['fixed_monthly_charge'] ?? 50.00;
            $vatPercentage = $systemSettings['vat_percentage'] ?? 5.00;
            
            // Get existing invoice customer IDs
            $existingCustomerIds = $existingInvoices->pluck('c_id')->toArray();
            
            // Generate invoices for ALL active customers who don't have invoices yet
            $generatedCount = 0;
            foreach ($allActiveCustomers as $customer) {
                // Skip if invoice already exists
                if (in_array($customer->c_id, $existingCustomerIds)) {
                    continue;
                }
                
                try {
                    // Create new invoice
                    $this->createCustomerMonthlyInvoice($customer, $monthDate, $serviceCharge, $vatPercentage);
                    $generatedCount++;
                } catch (\Exception $e) {
                    Log::error("Auto-generation failed for customer {$customer->c_id}: " . $e->getMessage());
                }
            }
            
            if ($generatedCount > 0) {
                Log::info("Auto-generated {$generatedCount} invoices for ALL customers in {$monthDate->format('F Y')}");
            }
            
        } catch (\Exception $e) {
            Log::error('Auto-generate missing invoices for ALL customers error: ' . $e->getMessage());
        }
    }

    /**
     * Get all active customers with active products (regardless of billing cycle)
     */
    private function getAllActiveCustomersWithProducts(Carbon $monthDate)
    {
        return DB::table('customers as c')
            ->select(
                'c.c_id',
                'c.name',
                'c.customer_id',
                'c.email',
                'c.phone',
                DB::raw('SUM(p.monthly_price * cp.billing_cycle_months) as total_product_amount'),
                DB::raw('GROUP_CONCAT(CONCAT(p.p_id, ":", p.monthly_price, ":", cp.billing_cycle_months, ":", cp.cp_id)) as product_details')
            )
            ->join('customer_to_products as cp', 'c.c_id', '=', 'cp.c_id')
            ->join('products as p', 'cp.p_id', '=', 'p.p_id')
            ->where('cp.status', 'active')
            ->where('cp.is_active', 1)
            ->where('c.is_active', 1)
            ->where('cp.assign_date', '<=', $monthDate->endOfMonth()) // Only include customers assigned before or during this month
            ->groupBy('c.c_id', 'c.name', 'c.customer_id', 'c.email', 'c.phone')
            ->orderBy('c.name')
            ->get()
            ->map(function($customer) {
                // Parse product details
                $productDetails = [];
                if ($customer->product_details) {
                    $products = explode(',', $customer->product_details);
                    foreach ($products as $product) {
                        list($p_id, $price, $cycle, $cp_id) = explode(':', $product);
                        $productDetails[] = [
                            'p_id' => $p_id,
                            'cp_id' => $cp_id,
                            'monthly_price' => $price,
                            'billing_cycle_months' => $cycle
                        ];
                    }
                }
                $customer->product_details = $productDetails;
                return $customer;
            });
    }

    /**
     * Generate monthly bills for a specific month (for ALL active customers with products)
     */
    public function generateMonthlyBillsForAll(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'include_service_charge' => 'sometimes|boolean'
        ]);

        try {
            $month = $request->month;
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            $displayMonth = $monthDate->format('F Y');
            $includeServiceCharge = $request->boolean('include_service_charge', true);

            // Get system settings
            $systemSettings = $this->getSystemSettings();
            $serviceCharge = $includeServiceCharge ? ($systemSettings['fixed_monthly_charge'] ?? 50.00) : 0;
            $vatPercentage = $systemSettings['vat_percentage'] ?? 5.00;

            // Get ALL active customers with products (not just those due based on billing cycle)
            $allCustomers = $this->getAllActiveCustomersWithProducts($monthDate);

            if ($allCustomers->isEmpty()) {
                return redirect()->back()->with('error', 'No active customers with products found for ' . $displayMonth . '.');
            }

            $generatedCount = 0;
            $errors = [];

            foreach ($allCustomers as $customer) {
                try {
                    // Check if invoice already exists for this customer and month
                    $existingInvoice = Invoice::where('c_id', $customer->c_id)
                        ->whereYear('issue_date', $monthDate->year)
                        ->whereMonth('issue_date', $monthDate->month)
                        ->first();

                    if (!$existingInvoice) {
                        // Create new invoice
                        $this->createCustomerMonthlyInvoice($customer, $monthDate, $serviceCharge, $vatPercentage);
                        $generatedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Customer {$customer->name}: " . $e->getMessage();
                    Log::error("Monthly bill generation failed for customer {$customer->c_id}: " . $e->getMessage());
                }
            }

            $message = "Generated $generatedCount monthly bills for all active customers in $displayMonth";
            
            if (!empty($errors)) {
                $message .= " (with " . count($errors) . " errors)";
            }

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'generated_count' => $generatedCount,
                    'warnings' => $errors
                ]);
            }
            
            return redirect()->route('admin.billing.monthly-bills', $month)
                ->with('success', $message)
                ->with('warnings', $errors);

        } catch (\Exception $e) {
            Log::error('Generate monthly bills for all error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate monthly bills: ' . $e->getMessage());
        }
    }

    /**
     * Generate monthly bills for a specific month (respecting billing cycles) - ORIGINAL METHOD
     */
    public function generateMonthlyBills(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'include_service_charge' => 'sometimes|boolean'
        ]);

        try {
            $month = $request->month;
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            $displayMonth = $monthDate->format('F Y');
            $includeServiceCharge = $request->boolean('include_service_charge', true);

            // Get system settings
            $systemSettings = $this->getSystemSettings();
            $serviceCharge = $includeServiceCharge ? ($systemSettings['fixed_monthly_charge'] ?? 50.00) : 0;
            $vatPercentage = $systemSettings['vat_percentage'] ?? 5.00;

            // Get customers who are due for billing in this month based on their billing cycles
            $dueCustomers = $this->getDueCustomersForMonth($monthDate);

            if ($dueCustomers->isEmpty()) {
                return redirect()->back()->with('error', 'No customers due for billing in ' . $displayMonth . ' based on their billing cycles.');
            }

            $generatedCount = 0;
            $errors = [];

            foreach ($dueCustomers as $customer) {
                try {
                    // Check if invoice already exists for this customer and month
                    $existingInvoice = Invoice::where('c_id', $customer->c_id)
                        ->whereYear('issue_date', $monthDate->year)
                        ->whereMonth('issue_date', $monthDate->month)
                        ->first();

                    if (!$existingInvoice) {
                        // Create new invoice
                        $this->createCustomerMonthlyInvoice($customer, $monthDate, $serviceCharge, $vatPercentage);
                        $generatedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Customer {$customer->name}: " . $e->getMessage();
                    Log::error("Monthly bill generation failed for customer {$customer->c_id}: " . $e->getMessage());
                }
            }

            $message = "Generated $generatedCount monthly bills for $displayMonth";
            
            if (!empty($errors)) {
                $message .= " (with " . count($errors) . " errors)";
            }

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'generated_count' => $generatedCount,
                    'warnings' => $errors
                ]);
            }
            
            return redirect()->route('admin.billing.monthly-bills', $month)
                ->with('success', $message)
                ->with('warnings', $errors);

        } catch (\Exception $e) {
            Log::error('Generate monthly bills error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate monthly bills: ' . $e->getMessage());
        }
    }

    /**
     * Get customers who are due for billing in specific month based on billing cycles
     */
    private function getDueCustomersForMonth(Carbon $monthDate)
    {
        return DB::table('customers as c')
            ->select(
                'c.c_id',
                'c.name',
                'c.customer_id',
                'c.email',
                'c.phone',
                DB::raw('SUM(p.monthly_price * cp.billing_cycle_months) as total_product_amount'),
                DB::raw('GROUP_CONCAT(CONCAT(p.p_id, ":", p.monthly_price, ":", cp.billing_cycle_months, ":", cp.cp_id)) as product_details')
            )
            ->join('customer_to_products as cp', 'c.c_id', '=', 'cp.c_id')
            ->join('products as p', 'cp.p_id', '=', 'p.p_id')
            ->where('cp.status', 'active')
            ->where('cp.is_active', 1)
            ->where('c.is_active', 1)
            ->where(function($query) use ($monthDate) {
                // Customers whose billing cycle falls in this month
                $query->where(function($q) use ($monthDate) {
                    // Monthly billing (billing_cycle_months = 1)
                    $q->where('cp.billing_cycle_months', 1)
                      ->where('cp.assign_date', '<=', $monthDate->endOfMonth());
                })
                // Quarterly, Semi-annual, Annual billing
                ->orWhere(function($q) use ($monthDate) {
                    $q->where('cp.billing_cycle_months', '>', 1)
                      ->whereRaw('DATE_ADD(cp.assign_date, INTERVAL cp.billing_cycle_months MONTH) > ?', 
                                [$monthDate->startOfMonth()])
                      ->where('cp.assign_date', '<=', $monthDate->startOfMonth());
                });
            })
            ->groupBy('c.c_id', 'c.name', 'c.customer_id', 'c.email', 'c.phone')
            ->orderBy('c.name')
            ->get()
            ->map(function($customer) {
                // Parse product details
                $productDetails = [];
                if ($customer->product_details) {
                    $products = explode(',', $customer->product_details);
                    foreach ($products as $product) {
                        list($p_id, $price, $cycle, $cp_id) = explode(':', $product);
                        $productDetails[] = [
                            'p_id' => $p_id,
                            'cp_id' => $cp_id,
                            'monthly_price' => $price,
                            'billing_cycle_months' => $cycle
                        ];
                    }
                }
                $customer->product_details = $productDetails;
                return $customer;
            });
    }

    /**
     * Create monthly invoice for a customer (respecting billing cycles)
     */
    private function createCustomerMonthlyInvoice($customer, Carbon $monthDate, $serviceCharge = 0.00, $vatPercentage = 0.00)
    {
        // Calculate total product amount from active products that are due this month
        $productAmount = $customer->total_product_amount ?? 0;

<<<<<<< HEAD
        // With VAT and service charges removed, total amount is just product amount plus previous due
        $totalAmount = $productAmount;
=======
        $subtotal = $productAmount + $serviceCharge;
        $vatAmount = $subtotal * ($vatPercentage / 100);
        $totalAmount = $subtotal + $vatAmount;
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770

        // Get previous due amount from unpaid invoices
        $previousDue = Invoice::where('c_id', $customer->c_id)
            ->where('status', '!=', 'paid')
            ->where('next_due', '>', 0)
            ->sum('next_due');

        $totalAmount += $previousDue;

        $invoice = Invoice::create([
            'invoice_number' => $this->generateInvoiceNumber(),
            'c_id' => $customer->c_id,
            'issue_date' => $monthDate->format('Y-m-d'),
            'previous_due' => $previousDue,
            'service_charge' => 0.00,
            'vat_percentage' => 0.00,
            'vat_amount' => 0.00,
            'subtotal' => $productAmount,
            'total_amount' => $totalAmount,
            'received_amount' => 0,
            'next_due' => $totalAmount,
            'status' => 'unpaid',
            'notes' => $this->generateBillingNotes($customer, $monthDate),
            'created_by' => \Illuminate\Support\Facades\Auth::id()
        ]);

        Log::info("Created invoice {$invoice->invoice_number} for customer {$customer->name} with amount ৳{$totalAmount}");

        return $invoice;
    }

    /**
     * Generate billing notes based on billing cycles
     */
    private function generateBillingNotes($customer, Carbon $monthDate)
    {
        $notes = [];
        
        foreach (($customer->product_details ?? []) as $product) {
            $cycleText = $this->getBillingCycleText($product['billing_cycle_months']);
            $notes[] = "{$cycleText} billing for {$product['billing_cycle_months']} month(s)";
        }
        
        $baseNote = 'Auto-generated: ' . implode(', ', $notes) . ' - Due for ' . $monthDate->format('F Y');
        
        // Add previous due info if any
        $previousDue = Invoice::where('c_id', $customer->c_id)
            ->where('status', '!=', 'paid')
            ->where('next_due', '>', 0)
            ->sum('next_due');
            
        if ($previousDue > 0) {
            $baseNote .= " (Includes ৳" . number_format($previousDue, 2) . " previous due)";
        }
        
        return $baseNote;
    }

    /**
     * Get human-readable billing cycle text
     */
    private function getBillingCycleText($months)
    {
        return match($months) {
            1 => 'Monthly',
            3 => 'Quarterly',
            6 => 'Semi-Annual',
            12 => 'Annual',
            default => "{$months}-Month"
        };
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $year = date('Y');
        $invoicePrefix = $prefix . '-' . $year . '-';
        
        // Find the highest existing invoice number with this prefix
        $lastInvoice = Invoice::where('invoice_number', 'like', $invoicePrefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            // Extract the numeric part and increment it
            $lastNumber = intval(substr($lastInvoice->invoice_number, strlen($invoicePrefix)));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Start from 0001 if no invoices exist with this prefix
            $newNumber = '0001';
        }
        
        return $invoicePrefix . $newNumber;
    }

    /**
     * Get available billing months based on customer assignment dates and billing cycles
     */
    private function getAvailableBillingMonths()
    {
        $months = collect();
        
        // Get the earliest customer product assignment date using DB query
        $earliestAssignment = DB::table('customer_to_products')
            ->whereNotNull('assign_date')
            ->orderBy('assign_date')
            ->first();
            
        if (!$earliestAssignment) {
            // If no assignments, use current month
            $months->push(Carbon::now()->format('Y-m'));
            return $months;
        }

        $startDate = Carbon::parse($earliestAssignment->assign_date)->startOfMonth();
        $currentDate = Carbon::now()->startOfMonth();
        
        // Add all months from earliest assignment to current month
        while ($startDate <= $currentDate) {
            $months->push($startDate->format('Y-m'));
            $startDate->addMonth();
        }

        return $months->unique()->sortDesc()->values();
    }

    /**
     * Get system settings for billing
     */
    private function getSystemSettings()
    {
        try {
            $settings = DB::table('system_settings')
                ->whereIn('key', ['fixed_monthly_charge', 'vat_percentage'])
                ->pluck('value', 'key')
                ->toArray();

            return [
                'fixed_monthly_charge' => isset($settings['fixed_monthly_charge']) ? floatval($settings['fixed_monthly_charge']) : 50.00,
                'vat_percentage' => isset($settings['vat_percentage']) ? floatval($settings['vat_percentage']) : 5.00
            ];
        } catch (\Exception $e) {
            Log::warning('Could not fetch system settings: ' . $e->getMessage());
            return [
                'fixed_monthly_charge' => 50.00,
                'vat_percentage' => 5.00
            ];
        }
    }



    /**
     * Get invoice details for modal view
     */
    public function getInvoiceDetails($invoiceId)
    {
        try {
            $invoice = Invoice::with([
                'customer',
                'payments',
                'customer.customerproducts.product'
            ])->findOrFail($invoiceId);

            $html = view('admin.billing.partials.invoice-details-modal', compact('invoice'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            Log::error('Get invoice details error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading invoice details'
            ], 500);
        }
    }

    /**
     * Record payment for monthly bill - WORKING VERSION
     */
    public function recordPayment(Request $request, $invoiceId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_banking,card,online',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'cp_id' => 'nullable|exists:customer_to_products,cp_id',
        ]);

        try {
            DB::beginTransaction();

            // Get invoice with customer
            $invoice = Invoice::with('customer')->findOrFail($invoiceId);
            $amount = $request->amount;
            $dueAmount = $invoice->next_due ?? $invoice->total_amount;

            // Validate amount
            if ($amount > $dueAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount (৳' . number_format($amount, 2) . ') cannot exceed due amount (৳' . number_format($dueAmount, 2) . ')'
                ], 422);
            }

            // Create payment record
            $paymentData = [
                'invoice_id' => $invoice->invoice_id,
                'c_id' => $invoice->c_id,
                'cp_id' => $request->cp_id, // Store the product being paid for
                'amount' => $amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'notes' => $request->notes,
                'collected_by' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : 1,
                'status' => 'completed',
            ];

            // Add transaction_id if payment method is not cash
            if ($request->payment_method !== 'cash' && $request->has('transaction_id')) {
                $paymentData['transaction_id'] = $request->transaction_id;
            }

            $payment = Payment::create($paymentData);

            // Update invoice
            $newReceivedAmount = $invoice->received_amount + $amount;
            $newDueAmount = max(0, $invoice->total_amount - $newReceivedAmount);

            // Determine new status - handle floating point precision
            if ($newDueAmount < 0.01) {
                $newDueAmount = 0;
                $status = 'paid';
            } elseif ($newReceivedAmount > 0) {
                $status = 'partial';
            } else {
                $status = 'unpaid';
            }

            $invoice->update([
                'received_amount' => $newReceivedAmount,
                'next_due' => $newDueAmount,
                'status' => $status
            ]);

            DB::commit();

            // Get product name for success message
            $productName = '';
            if ($request->cp_id) {
                $customerProduct = \App\Models\CustomerProduct::find($request->cp_id);
                if ($customerProduct && $customerProduct->product) {
                    $productName = ' for ' . $customerProduct->product->name;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment of ৳' . number_format($amount, 2) . $productName . ' recorded successfully!',
                'invoice_id' => $invoice->invoice_id,
                'new_status' => $status,
                'new_due' => $newDueAmount,
                'new_received' => $newReceivedAmount,
                'payment_id' => $payment->payment_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Record payment error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to record payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoice data for payment modal
     */
    public function getInvoiceData($invoiceId)
    {
        try {
            $invoice = Invoice::with(['customer.customerproducts.product'])
                ->where('invoice_id', $invoiceId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'invoice' => [
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $invoice->total_amount,
                    'next_due' => $invoice->next_due,
                    'received_amount' => $invoice->received_amount,
                    'status' => $invoice->status,
                    'customer' => [
                        'name' => $invoice->customer->name ?? 'N/A',
                        'email' => $invoice->customer->email ?? 'N/A',
                        'phone' => $invoice->customer->phone ?? 'N/A',
                        'customerproducts' => $invoice->customer->customerproducts->map(function($cp) {
                            return [
                                'product' => [
                                    'name' => $cp->product->name ?? 'Unknown',
                                    'monthly_price' => $cp->product->monthly_price ?? 0,
                                ],
                                'billing_cycle_months' => $cp->billing_cycle_months ?? 1,
                            ];
                        })
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found'
            ], 404);
        }
    }

    /**
     * Quick test method to check if invoices exist
     */
    public function testInvoices($month)
    {
        try {
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            
            $invoices = Invoice::with('customer')
                ->whereYear('issue_date', $monthDate->year)
                ->whereMonth('issue_date', $monthDate->month)
                ->get();

            return response()->json([
                'month' => $month,
                'invoices_count' => $invoices->count(),
                'invoices' => $invoices->map(function($invoice) {
                    return [
                        'invoice_id' => $invoice->invoice_id,
                        'invoice_number' => $invoice->invoice_number,
                        'customer_name' => $invoice->customer->name ?? 'No Customer',
                        'total_amount' => $invoice->total_amount,
                        'received_amount' => $invoice->received_amount,
                        'next_due' => $invoice->next_due,
                        'status' => $invoice->status
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer products for a specific month
     */
    public function getCustomerproducts($customerId, $month)
    {
        try {
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            
            $customer = Customer::with([
                'customerproducts' => function($query) use ($monthDate) {
                    $query->where('status', 'active')
                          ->where('is_active', true)
                          ->with('product');
                }
            ])->findOrFail($customerId);

            return response()->json([
                'success' => true,
                'customer' => $customer->name,
                'products' => $customer->customerproducts->map(function($cp) {
                    return [
                        'product_name' => $cp->product->name,
                        'monthly_price' => $cp->product->monthly_price,
                        'billing_cycle' => $cp->billing_cycle_months,
                        'total_amount' => $cp->product->monthly_price * $cp->billing_cycle_months
                    ];
                }),
                'total_monthly' => $customer->customerproducts->sum(function($cp) {
                    return $cp->product->monthly_price;
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Get customer products error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading customer products'
            ], 500);
        }
    }
       
    public function handleMonthlyBills(Request $request, $month)
    {
        $request->validate([
            'action' => 'required|string',
            'invoice_ids' => 'sometimes|array',
            'invoice_ids.*' => 'exists:invoices,invoice_id'
        ]);

        try {
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            $action = $request->action;
            $invoiceIds = $request->invoice_ids ?? [];

            switch ($action) {
                case 'bulk_update_status':
                    return $this->bulkUpdateStatus($invoiceIds, $request->status, $monthDate);
                    
                case 'regenerate_bills':
                    return $this->regenerateBills($monthDate);
                    
                case 'export_data':
                    return $this->exportMonthlyBills($monthDate);
                    
                case 'recalculate_totals':
                    return $this->recalculateTotals($monthDate);
                    
                default:
                    return redirect()->back()->with('error', 'Invalid action specified.');
            }

        } catch (\Exception $e) {
            Log::error('Handle monthly bills error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to process request: ' . $e->getMessage());
        }
    }



    /**
     * Bulk update invoice status
     */
    private function bulkUpdateStatus($invoiceIds, $status, Carbon $monthDate)
    {
        try {
            $validStatuses = ['paid', 'unpaid', 'partial', 'cancelled'];
            
            if (!in_array($status, $validStatuses)) {
                return redirect()->back()->with('error', 'Invalid status specified.');
            }

            $invoices = Invoice::whereIn('invoice_id', $invoiceIds)->get();
            $updatedCount = 0;

            foreach ($invoices as $invoice) {
                // For paid status, mark as fully paid
                if ($status === 'paid') {
                    $invoice->update([
                        'received_amount' => $invoice->total_amount,
                        'next_due' => 0,
                        'status' => 'paid'
                    ]);
                } 
                // For unpaid status, reset payments
                elseif ($status === 'unpaid') {
                    $invoice->update([
                        'received_amount' => 0,
                        'next_due' => $invoice->total_amount,
                        'status' => 'unpaid'
                    ]);
                }
                // For other statuses, just update the status
                else {
                    $invoice->update(['status' => $status]);
                }
                
                $updatedCount++;
            }

            return redirect()->back()->with('success', "Updated status for {$updatedCount} invoices to {$status}");

        } catch (\Exception $e) {
            throw new \Exception("Bulk status update failed: " . $e->getMessage());
        }
    }

    /**
     * Regenerate bills for the month (useful for corrections)
     */
    private function regenerateBills(Carbon $monthDate)
    {
        try {
            DB::beginTransaction();

            // Delete existing invoices for the month
            $deletedCount = Invoice::whereYear('issue_date', $monthDate->year)
                ->whereMonth('issue_date', $monthDate->month)
                ->delete();

            // Regenerate bills using your existing logic
            // You might want to call your generateMonthlyBills logic here
            $systemSettings = $this->getSystemSettings();
            $dueCustomers = $this->getDueCustomersForMonth($monthDate);
            $regeneratedCount = 0;

            foreach ($dueCustomers as $customer) {
                $this->createCustomerMonthlyInvoice($customer, $monthDate, 
                    $systemSettings['fixed_monthly_charge'], 
                    $systemSettings['vat_percentage']);
                $regeneratedCount++;
            }

            DB::commit();

            return redirect()->back()->with('success', "Regenerated {$regeneratedCount} bills for " . $monthDate->format('F Y'));

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Regenerate bills failed: " . $e->getMessage());
        }
    }

    /**
     * Export monthly bills data
     */
    private function exportMonthlyBills(Carbon $monthDate)
    {
        try {
            $invoices = Invoice::with(['customer', 'payments'])
                ->whereYear('issue_date', $monthDate->year)
                ->whereMonth('issue_date', $monthDate->month)
                ->get();

            // In a real implementation, you would generate CSV/Excel file
            // For now, we'll just log and return success
            Log::info('Monthly bills export prepared', [
                'month' => $monthDate->format('F Y'),
                'invoice_count' => $invoices->count(),
                'total_amount' => $invoices->sum('total_amount')
            ]);

            return redirect()->back()->with('success', 'Export data prepared for ' . $monthDate->format('F Y') . ' (' . $invoices->count() . ' invoices)');

        } catch (\Exception $e) {
            throw new \Exception("Export failed: " . $e->getMessage());
        }
    }

    /**
     * Recalculate totals for the month (useful if there were data issues)
     */
    private function recalculateTotals(Carbon $monthDate)
    {
        try {
            DB::beginTransaction();

            $invoices = Invoice::with('payments')
                ->whereYear('issue_date', $monthDate->year)
                ->whereMonth('issue_date', $monthDate->month)
                ->get();

            $recalculatedCount = 0;

            foreach ($invoices as $invoice) {
                $totalReceived = $invoice->payments->sum('amount');
                $nextDue = max(0, $invoice->total_amount - $totalReceived);
                
                // Determine status based on payments
                if ($nextDue <= 0) {
                    $status = 'paid';
                } elseif ($totalReceived > 0) {
                    $status = 'partial';
                } else {
                    $status = 'unpaid';
                }

                $invoice->update([
                    'received_amount' => $totalReceived,
                    'next_due' => $nextDue,
                    'status' => $status
                ]);

                $recalculatedCount++;
            }

            DB::commit();

            return redirect()->back()->with('success', "Recalculated totals for {$recalculatedCount} invoices");

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Recalculate totals failed: " . $e->getMessage());
        }
    }

    /**
     * Generate invoices for all customers
     */
    public function generateAllInvoices(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'force' => 'nullable|boolean'
        ]);
        
        $month = $request->month;
        $force = $request->force ?? false;
        
        try {
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            $displayMonth = $monthDate->format('F Y');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid month format. Please use YYYY-MM format.'
            ], 400);
        }
        
        // Get all active customers with active products
        $customers = $this->getAllActiveCustomersWithProducts($monthDate);
        
        if ($customers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "No active customers with products found for {$displayMonth}."
            ]);
        }
        
        $generatedCount = 0;
        $skippedCount = 0;
        $errors = [];
        
        foreach ($customers as $customer) {
            try {
                // Check if invoice already exists for this customer and month
                $existingInvoice = Invoice::where('c_id', $customer->c_id)
                    ->whereYear('issue_date', $monthDate->year)
                    ->whereMonth('issue_date', $monthDate->month)
                    ->first();
                
                if ($existingInvoice && !$force) {
                    $skippedCount++;
                    continue;
                }
                
                if ($existingInvoice && $force) {
                    $existingInvoice->delete();
                }
                
                // Create new invoice
                $invoice = $this->createCustomerMonthlyInvoice($customer, $monthDate);
                
                if ($invoice) {
                    $generatedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Customer {$customer->name}: " . $e->getMessage();
                Log::error("Invoice generation failed for customer {$customer->c_id}: " . $e->getMessage());
            }
        }
        
        $message = "Generated {$generatedCount} invoices for all customers in {$displayMonth}";
        
        if ($skippedCount > 0) {
            $message .= " ({$skippedCount} customers already had invoices)";
        }
        
        if (!empty($errors)) {
            $message .= " (" . count($errors) . " errors occurred)";
        }
        
        $response = [
            'success' => true,
            'message' => $message,
            'generated_count' => $generatedCount,
            'skipped_count' => $skippedCount
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        return response()->json($response);
    }
       
}
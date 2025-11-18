<?php
// app/Http/Controllers/Admin/CustomerProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerProduct;
<<<<<<< HEAD
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
=======
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770

class CustomerProductController extends Controller
{
    /** 🏠 Show all customer products with search */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            $status = $request->get('status');
            $productType = $request->get('product_type');

            // Build query with search and filters - FIXED: Use customerproducts instead of activeCustomerproducts
            $customersQuery = Customer::with(['customerProducts.product' => function($query) {
                    $query->orderBy('product_type_id', 'desc');
                }])
                ->whereHas('customerProducts', function($query) use ($search, $status, $productType) {
                    if ($status) {
                        $query->where('status', $status);
                    }
                    if ($productType) {
                        $query->whereHas('product', function($q) use ($productType) {
                            $q->where('product_type_id', $productType);
                        });
                    }
                });

            // Apply search filter
            if ($search) {
                $customersQuery->where(function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%')
                          ->orWhere('phone', 'like', '%' . $search . '%')
                          ->orWhere('customer_id', 'like', '%' . $search . '%');
                });
            }

            $customers = $customersQuery->orderBy('name')->paginate(10);

            // Keep the same stats calculation
            $totalCustomers = Customer::count();
            $activeProducts = CustomerProduct::active()->count();
            $monthlyRevenue = DB::table('customer_to_products as cp')
                ->join('products as p', 'cp.p_id', '=', 'p.p_id')
                ->where('cp.status', 'active')
                ->where('cp.is_active', 1)
                ->select(DB::raw('COALESCE(SUM(p.monthly_price), 0) as total_revenue'))
                ->first()->total_revenue ?? 0;
            $renewalsDue = CustomerProduct::active()
                ->where('due_date', '<=', now()->addDays(7))
                ->count();

            return view('admin.customer-to-products.index', compact(
                'customers',
                'totalCustomers',
                'activeProducts',
                'monthlyRevenue',
                'renewalsDue'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading customer products index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load products data.');
        }
    }

    /** ➕ Assign product to customer */
    public function assign()
    {
        try {
            $customers = Customer::where('is_active', true)
                ->orderBy('name')
                ->get(['c_id', 'name', 'phone', 'email', 'customer_id', 'address']);
            
            $products = Product::orderBy('product_type_id')->orderBy('monthly_price')->get();
            
            return view('admin.customer-to-products.assign', compact('customers', 'products'));
        } catch (\Exception $e) {
            Log::error('Error loading assign product form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load assignment form.');
        }
    }

    /** 💾 Store assigned products  */
    public function store(Request $request)
    {
        // Log the request for debugging
        Log::info('Product assignment request received:', $request->all());

        $request->validate([
            'customer_id' => 'required|exists:customers,c_id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,p_id',
            'products.*.billing_cycle_months' => 'required|integer|min:1|max:12',
            'products.*.assign_date' => 'required|date|before_or_equal:today',
<<<<<<< HEAD
            'products.*.due_date_day' => 'required|integer|min:1|max:28',
=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
        ]);

        $customerId = $request->customer_id;
        $products = $request->products;

        try {
            DB::beginTransaction();

            // Check for duplicate products in the same request
            $productIds = collect($products)->pluck('product_id');
            if ($productIds->count() !== $productIds->unique()->count()) {
                DB::rollBack();
                return back()->with('error', 'You cannot assign the same product multiple times in the same request.')
                            ->withInput();
            }

            $assignedProducts = [];
            $errors = [];
<<<<<<< HEAD
            $invoicesGenerated = [];
=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770

            foreach ($products as $index => $productData) {
                $productId = $productData['product_id'];
                
                // Check if product is already assigned to this customer (active or inactive)
                $existingProduct = CustomerProduct::where('c_id', $customerId)
                    ->where('p_id', $productId)
                    ->first();

                if ($existingProduct) {
                    $productName = Product::find($productId)->name ?? 'Unknown product';
                    
                    // Check if the existing product is active
                    if ($existingProduct->is_active && $existingProduct->status === 'active') {
                        $errors[] = "Product '{$productName}' is already actively assigned to this customer. Please choose a different product.";
                    } else {
                        $errors[] = "Product '{$productName}' was previously assigned to this customer. Please choose a different product.";
                    }
                    continue;
                }

<<<<<<< HEAD
                // Calculate due_date based on assign_date and due_date_day
                $assignDate = $productData['assign_date'];
                $dueDateDay = (int) $productData['due_date_day'];
                $billingCycleMonths = (int) $productData['billing_cycle_months'];
                
                // Calculate the due date as the end of billing period with the specified day
                $assignDateCarbon = \Carbon\Carbon::parse($assignDate);
                $dueDate = $assignDateCarbon->copy()->addMonths($billingCycleMonths);
                $dueDate->day($dueDateDay);
                
=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                // Create the product assignment
                $customerProduct = CustomerProduct::create([
                    'c_id' => $customerId,
                    'p_id' => $productId,
<<<<<<< HEAD
                    'assign_date' => $assignDate,
                    'billing_cycle_months' => $billingCycleMonths,
                    'due_date' => $dueDate,
=======
                    'assign_date' => $productData['assign_date'],
                    'billing_cycle_months' => $productData['billing_cycle_months'],
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                    'status' => 'active',
                    'is_active' => 1,
                ]);

                $assignedProducts[] = $customerProduct;
                Log::info("Product assigned successfully:", [
                    'customer_id' => $customerId,
                    'product_id' => $productId,
                    'cp_id' => $customerProduct->cp_id
                ]);
<<<<<<< HEAD
                
                // Automatically generate invoices for current and future billing periods
                $generatedInvoices = $this->generateAutomaticInvoices($customerProduct, $customerId);
                $invoicesGenerated = array_merge($invoicesGenerated, $generatedInvoices);
=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
            }

            if (!empty($errors)) {
                DB::rollBack();
                return back()
                    ->with('error', implode(' ', $errors))
                    ->withInput();
            }

            if (empty($assignedProducts)) {
                DB::rollBack();
                return back()
                    ->with('error', 'No products were assigned. Please check your selection.')
                    ->withInput();
            }

            DB::commit();

            $successMessage = count($assignedProducts) . ' product(s) assigned successfully!';
<<<<<<< HEAD
            if (!empty($invoicesGenerated)) {
                $successMessage .= ' ' . count($invoicesGenerated) . ' invoice(s) automatically generated.';
            }
            
=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
            return redirect()->route('admin.customer-to-products.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product assignment failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->with('error', 'Failed to assign products: ' . $e->getMessage())
                ->withInput();
        }
    }

<<<<<<< HEAD
    /** 
     * Automatically generate invoices for a customer product
     * This method generates invoices for current and future billing periods
     */
    private function generateAutomaticInvoices($customerProduct, $customerId)
    {
        $generatedInvoices = [];
        try {
            $assignDate = Carbon::parse($customerProduct->assign_date);
            $billingCycleMonths = $customerProduct->billing_cycle_months;
            
            // Get the customer and product details
            $customer = Customer::find($customerId);
            $product = Product::find($customerProduct->p_id);
            
            if (!$customer || !$product) {
                return $generatedInvoices;
            }
            
            // Calculate the end date for invoice generation (6 months from now)
            $endDate = Carbon::now()->addMonths(6);
            
            // Generate invoices for each billing period
            $currentPeriodStart = $assignDate->copy();
            
            // Generate invoice for the current period if it's due
            $currentMonthStart = Carbon::now()->startOfMonth();
            
            // Check if this product should have an invoice for the current month
            // A product should have an invoice in a month if:
            // 1. It was assigned before or during this month
            // 2. The billing cycle matches (e.g., monthly products every month, quarterly every 3 months)
            
            // Generate invoices for up to 6 months
            for ($i = 0; $i < 6; $i++) {
                $invoiceMonth = $currentMonthStart->copy()->addMonths($i);
                
                // Check if this product should be billed in this month
                if ($this->shouldBillInMonth($customerProduct, $invoiceMonth)) {
                    // Check if invoice already exists for this period
                    $existingInvoice = Invoice::where('cp_id', $customerProduct->cp_id)
                        ->whereYear('issue_date', $invoiceMonth->year)
                        ->whereMonth('issue_date', $invoiceMonth->month)
                        ->first();
                    
                    if (!$existingInvoice) {
                        // Generate invoice for this period
                        $invoice = $this->createInvoiceForPeriod($customerProduct, $invoiceMonth);
                        if ($invoice) {
                            $generatedInvoices[] = $invoice;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Automatic invoice generation failed: ' . $e->getMessage());
        }
        
        return $generatedInvoices;
    }
    
    /**
     * Determine if a customer product should be billed in a specific month
     */
    private function shouldBillInMonth($customerProduct, $billingMonth)
    {
        try {
            $assignDate = Carbon::parse($customerProduct->assign_date);
            $billingCycleMonths = $customerProduct->billing_cycle_months;
            
            // Product must be assigned before or during the billing month
            if ($assignDate->greaterThan($billingMonth->copy()->endOfMonth())) {
                return false;
            }
            
            // For monthly billing, always bill
            if ($billingCycleMonths == 1) {
                return true;
            }
            
            // For other cycles, check if this month is a billing month
            // Calculate the first billing date (assign date + billing cycle)
            $firstBillingDate = $assignDate->copy()->addMonths($billingCycleMonths);
            
            // If the first billing date is after the billing month, not a billing month
            if ($firstBillingDate->greaterThan($billingMonth->copy()->endOfMonth())) {
                return false;
            }
            
            // Calculate months difference between first billing date and billing month
            $monthsDiff = $firstBillingDate->diffInMonths($billingMonth);
            
            // Check if this month is a multiple of the billing cycle from the first billing date
            return ($monthsDiff % $billingCycleMonths) === 0;
        } catch (\Exception $e) {
            Log::error('Error checking if product should be billed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create invoice for a specific billing period
     */
    private function createInvoiceForPeriod($customerProduct, $billingPeriodEnd)
    {
        try {
            // Calculate amounts based on product and billing cycle
            $productAmount = $customerProduct->product->monthly_price * $customerProduct->billing_cycle_months;
            
            // Subtotal can be manually overridden, but defaults to calculated amount
            $subtotal = $productAmount;
            $totalAmount = $subtotal;
            
            // Get previous due amount from unpaid invoices for this customer
            $previousDue = Invoice::whereHas('customerProduct', function($q) use ($customerProduct) {
                    $q->where('c_id', $customerProduct->c_id);
                })
                ->where('status', '!=', 'paid')
                ->where('next_due', '>', 0)
                ->sum('next_due');
                
            $totalAmount += $previousDue;
            
            // Generate unique invoice number
            $invoiceNumber = $this->generateInvoiceNumber();
            
            // Create the invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'cp_id' => $customerProduct->cp_id,
                'issue_date' => $billingPeriodEnd->format('Y-m-d'),
                'previous_due' => $previousDue,
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'received_amount' => 0,
                'next_due' => $totalAmount,
                'status' => 'unpaid',
                'notes' => "Auto-generated invoice for {$customerProduct->product->name} - Due for " . $billingPeriodEnd->format('F Y'),
                'created_by' => auth()->id() ?? 1
            ]);
            
            Log::info("Auto-generated invoice {$invoice->invoice_number} for customer {$customerProduct->customer->name}");
            
            return $invoice;
        } catch (\Exception $e) {
            Log::error('Failed to create invoice for period: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $year = date('Y');
        $lastInvoice = Invoice::whereYear('created_at', $year)->latest('invoice_id')->first();

        if ($lastInvoice && preg_match('/INV-\d{4}-(\d+)/', $lastInvoice->invoice_number, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . '-' . $year . '-' . $newNumber;
    }

    // app/Http/Controllers/Admin/CustomerProductController.php

/** 🔍 Check if product already exists for customer */
public function checkExistingProduct(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,c_id',
        'product_id' => 'required|exists:products,p_id',
    ]);

    try {
        $customerId = $request->customer_id;
        $productId = $request->product_id;

        $existingProduct = CustomerProduct::where('c_id', $customerId)
            ->where('p_id', $productId)
            ->first();

        $productName = Product::find($productId)->name ?? 'Unknown product';

        if ($existingProduct) {
            if ($existingProduct->is_active && $existingProduct->status === 'active') {
                return response()->json([
                    'exists' => true,
                    'message' => 'This customer already has the "' . $productName . '" product actively assigned. Please choose a different product.'
                ]);
            } else {
                return response()->json([
                    'exists' => true,
                    'message' => 'This customer previously had the "' . $productName . '" product. Please choose a different product.'
                ]);
            }
        }

        return response()->json([
            'exists' => false,
            'message' => 'Product is available for assignment.'
        ]);

    } catch (\Exception $e) {
        Log::error('Error checking existing product: ' . $e->getMessage());
        return response()->json([
            'exists' => false,
            'message' => 'Error checking product availability.'
        ], 500);
    }
}
=======
    /** 🔍 Check if product already exists for customer */
    public function checkExistingProduct(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,c_id',
            'product_id' => 'required|exists:products,p_id',
        ]);

        try {
            $customerId = $request->customer_id;
            $productId = $request->product_id;

            $existingProduct = CustomerProduct::where('c_id', $customerId)
                ->where('p_id', $productId)
                ->first();

            $productName = Product::find($productId)->name ?? 'Unknown product';

            if ($existingProduct) {
                if ($existingProduct->is_active && $existingProduct->status === 'active') {
                    return response()->json([
                        'exists' => true,
                        'message' => 'This customer already has the "' . $productName . '" product actively assigned. Please choose a different product.'
                    ]);
                } else {
                    return response()->json([
                        'exists' => true,
                        'message' => 'This customer previously had the "' . $productName . '" product. Please choose a different product.'
                    ]);
                }
            }

            return response()->json([
                'exists' => false,
                'message' => 'Product is available for assignment.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking existing product: ' . $e->getMessage());
            return response()->json([
                'exists' => false,
                'message' => 'Error checking product availability.'
            ], 500);
        }
    }
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770

    /** ✏️ Edit existing product */
    public function edit($id)
    {
        try {
            $customerProduct = CustomerProduct::with(['customer', 'product'])->find($id);

            if (!$customerProduct) {
                return redirect()->route('admin.customer-to-products.index')
                    ->with('error', 'Product assignment not found.');
            }

            $products = Product::orderBy('product_type_id')->orderBy('monthly_price')->get();
            
            return view('admin.customer-to-products.edit', [
                'customerProduct' => $customerProduct,
                'customer' => $customerProduct->customer, // Pass customer separately
                'product' => $customerProduct->product,   // Pass product separately
                'products' => $products
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading product edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load edit form.');
        }
    }

    /** 🔄 Update product details or status */
    public function update(Request $request, $id)
    {
        $request->validate([
<<<<<<< HEAD
            'assign_date' => 'required|date',
            'due_date_day' => 'required|integer|min:1|max:28',
=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
            'billing_cycle_months' => 'required|integer|min:1|max:12',
            'status' => 'required|in:active,pending,expired',
        ]);

        try {
            $customerProduct = CustomerProduct::find($id);
            
            if (!$customerProduct) {
                return redirect()->route('admin.customer-to-products.index')
                    ->with('error', 'Product assignment not found.');
            }

<<<<<<< HEAD
            // Calculate due_date based on assign_date and due_date_day
            $assignDate = $request->assign_date;
            $dueDateDay = (int) $request->due_date_day;
            $billingCycleMonths = (int) $request->billing_cycle_months;
            
            // Calculate the due date as the end of billing period with the specified day
            $assignDateCarbon = \Carbon\Carbon::parse($assignDate);
            $dueDate = $assignDateCarbon->copy()->addMonths($billingCycleMonths);
            $dueDate->day($dueDateDay);
            
            $customerProduct->update([
                'assign_date' => $assignDate,
                'billing_cycle_months' => $billingCycleMonths,
                'due_date' => $dueDate,
=======
            $customerProduct->update([
                'billing_cycle_months' => $request->billing_cycle_months,
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
                'status' => $request->status,
                'is_active' => $request->status === 'active' ? 1 : 0,
            ]);

            return redirect()->route('admin.customer-to-products.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update product.');
        }
    }

    /** 🔄 Toggle product status (active/expired) */
    public function toggleStatus($id)
    {
        try {
            $customerProduct = CustomerProduct::find($id);
            
            if (!$customerProduct) {
                return redirect()->route('admin.customer-to-products.index')
                    ->with('error', 'Product assignment not found.');
            }

            // Toggle between active and expired
            $newStatus = $customerProduct->status === 'active' ? 'expired' : 'active';
            
            $customerProduct->update([
                'status' => $newStatus,
                'is_active' => $newStatus === 'active' ? 1 : 0,
            ]);

            $action = $newStatus === 'active' ? 'activated' : 'paused';
            
            return redirect()->route('admin.customer-to-products.index')
                ->with('success', "Product {$action} successfully!");

        } catch (\Exception $e) {
            Log::error('Error toggling product status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to toggle product status.');
        }
    }

    /** ❌ Delete a customer's product */
    public function destroy($id)
    {
        try {
            $customerProduct = CustomerProduct::find($id);
            
            if (!$customerProduct) {
                return redirect()->route('admin.customer-to-products.index')
                    ->with('error', 'Product assignment not found.');
            }

            $productName = $customerProduct->product->name ?? 'Unknown product';
            $customerProduct->delete();

            return redirect()->route('admin.customer-to-products.index')
                ->with('success', "Product '{$productName}' removed successfully!");

        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete product.');
        }
    }

    /** ♻️ Renew customer product */
    public function renew($id)
    {
        try {
            $customerProduct = CustomerProduct::find($id);
            
            if (!$customerProduct) {
                return redirect()->back()->with('error', 'Product assignment not found.');
            }

            $customerProduct->update([
                'billing_cycle_months' => $customerProduct->billing_cycle_months + 1,
                'status' => 'active',
                'is_active' => 1,
            ]);

            return redirect()->back()->with('success', 'Product renewed successfully!');
            
        } catch (\Exception $e) {
            Log::error('Error renewing product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to renew product.');
        }
    }

    /** 🔍 Search customers for AJAX requests */
    public function searchCustomers(Request $request)
    {
        try {
            $searchTerm = $request->get('q');
            
            $customers = Customer::where(function($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                          ->orWhere('email', 'like', '%' . $searchTerm . '%')
                          ->orWhere('customer_id', 'like', '%' . $searchTerm . '%');
                })
                ->where('is_active', true)
                ->limit(10)
                ->get(['c_id', 'name', 'phone', 'email', 'customer_id', 'address']);
            
            return response()->json($customers);

        } catch (\Exception $e) {
            Log::error('Error searching customers: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
<<<<<<< HEAD

    /** ➕ Store new customer via AJAX */
    public function storeCustomer(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'customer_id' => 'required|string|max:50|unique:customers,customer_id',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:500',
            ]);

            $customer = Customer::create([
                'name' => $request->name,
                'customer_id' => $request->customer_id,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!',
                'customer' => $customer
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /** 🔍 Get customer suggestions for autocomplete */
    public function getCustomerSuggestions(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (empty($query)) {
                return response()->json([]);
            }

            $customers = Customer::where(function($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('phone', 'like', '%' . $query . '%')
                      ->orWhere('email', 'like', '%' . $query . '%')
                      ->orWhere('customer_id', 'like', '%' . $query . '%');
                })
                ->where('is_active', true)
                ->limit(10)
                ->get(['c_id', 'name', 'phone', 'email', 'customer_id']);

            return response()->json($customers);

        } catch (\Exception $e) {
            Log::error('Error fetching customer suggestions: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
=======
>>>>>>> 022ca1b083b8ee467518f7776a293591bd863770
}
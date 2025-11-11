<?php
// app/Http/Controllers/Admin/CustomerProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            
            $packages = Product::orderBy('product_type_id')->orderBy('monthly_price')->get();
            
            return view('admin.customer-to-products.assign', compact('customers', 'packages'));
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

            // Create the product assignment
            $customerProduct = CustomerProduct::create([
                'c_id' => $customerId,
                'p_id' => $productId,
                'assign_date' => $productData['assign_date'],
                'billing_cycle_months' => $productData['billing_cycle_months'],
                'status' => 'active',
                'is_active' => 1,
            ]);

            $assignedProducts[] = $customerProduct;
            Log::info("Product assigned successfully:", [
                'customer_id' => $customerId,
                'product_id' => $productId,
                'cp_id' => $customerProduct->cp_id
            ]);
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
            'billing_cycle_months' => 'required|integer|min:1|max:12',
            'status' => 'required|in:active,pending,expired',
        ]);

        try {
            $customerProduct = CustomerProduct::find($id);
            
            if (!$customerProduct) {
                return redirect()->route('admin.customer-to-products.index')
                    ->with('error', 'Product assignment not found.');
            }

            $customerProduct->update([
                'billing_cycle_months' => $request->billing_cycle_months,
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
}
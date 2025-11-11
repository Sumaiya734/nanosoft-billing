<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer as CustomerModel;
use App\Models\product;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class CustomerController extends Controller
{
    // ========== CUSTOMER AUTHENTICATION METHODS ==========
    
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->role === 'customer') {
                $request->session()->regenerate();
                return redirect()->route('customer.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. Customer login only.',
                ])->withInput();
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $customer = CustomerModel::where('user_id', $user->id)->first();
        
        if (!$customer) {
            Auth::logout();
            return redirect()->route('customer.login')->withErrors([
                'email' => 'Customer profile not found.',
            ]);
        }

        // Get customer's latest invoices and payments - FIXED COLUMN NAMES
        $invoices = Invoice::where('c_id', $customer->c_id)
            ->latest()
            ->take(5)
            ->get();

        $payments = Payment::where('c_id', $customer->c_id)
            ->latest()
            ->take(5)
            ->get();

        $totalDue = Invoice::where('c_id', $customer->c_id)
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('total_amount - received_amount'));

        return view('customer.dashboard', compact('customer', 'invoices', 'payments', 'totalDue'));
    }

    // ========== ADMIN CUSTOMER MANAGEMENT METHODS ==========
    
    public function index(Request $request)
    {
        // Get customers with products relationship
        $query = CustomerModel::with(['user', 'invoices', 'customerproducts.product']);

        // Apply filters
        switch ($request->get('filter')) {
            case 'active':
                $query->where('is_active', true);
                break;
            case 'inactive':
                $query->where('is_active', false);
                break;
            case 'with_due':
                $query->whereHas('invoices', function($q) {
                    $q->whereIn('status', ['unpaid', 'partial']);
                });
                break;
            case 'with_addons':
                // Filter for customers with special products
                $query->whereHas('customerproducts.product', function($q) {
                    $q->where('product_type', 'special');
                });
                break;
        }

        // Apply search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10);

        // Calculate statistics
        $totalCustomers = CustomerModel::count();
        $activeCustomers = CustomerModel::where('is_active', true)->count();
        $inactiveCustomers = CustomerModel::where('is_active', false)->count();
        $customersWithDue = CustomerModel::whereHas('invoices', function($q) {
            $q->whereIn('status', ['unpaid', 'partial']);
        })->count();

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'inactiveCustomers',
            'customersWithDue'
        ));
    }

    public function create()
    {
        $regularproducts = product::whereHas('type', function($query) {
            $query->where('name', 'regular');
        })->get();
        
        $specialproducts = product::whereHas('type', function($query) {
            $query->where('name', 'special');
        })->get();
        
        return view('admin.customers.create', compact('regularproducts', 'specialproducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string|max:30',
        'address' => 'required|string|max:500',
        'connection_address' => 'nullable|string|max:500',
        'id_type' => 'nullable|string|in:NID,Passport,Driving License', 
        'id_number' => 'nullable|string|max:100', 
        'regular_product_id' => 'nullable|exists:products,p_id',
        'special_product_ids' => 'nullable|array',
        'special_product_ids.*' => 'exists:products,p_id',
        'is_active' => 'sometimes|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Create User account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'), // Default password
                'role' => 'customer',
                'email_verified_at' => now(),
            ]);

            // Create Customer profile
            $customer = CustomerModel::create([
                'user_id' => $user->id,
                'customer_id' => CustomerModel::generateCustomerId(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'connection_address' => $request->connection_address,
                'id_type' => $request->id_type,
                'id_number' => $request->id_number,
                'is_active' => $request->has('is_active') ? $request->is_active : true,
            ]);

            // Assign Regular product ONLY if provided
        if ($request->filled('regular_product_id')) {
            $regularPkg = product::find($request->regular_product_id);
            if ($regularPkg) {
                $customer->assignproduct(
                    $regularPkg->p_id,
                    $regularPkg->monthly_price,
                    1, // billingCycleMonths
                    'active'
                );
            }
        }


           // Assign Special products
        if ($request->filled('special_product_ids')) {
            foreach ($request->special_product_ids as $pkgId) {
                $pkg = product::find($pkgId);
                if ($pkg) {
                    $customer->assignproduct(
                        $pkg->p_id,
                        $pkg->monthly_price,
                        1, // billingCycleMonths
                        'active'
                    );
                }
            }
        }

            DB::commit();

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer created successfully! Customer ID: ' . $customer->customer_id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating customer: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $customer = CustomerModel::with(['user', 'invoices', 'payments', 'customerproducts.product'])->findOrFail($id);
        
        // Calculate statistics
        $totalInvoices = $customer->invoices->count();
        $totalPaid = $customer->invoices->where('status', 'paid')->sum('total_amount');
        $totalDue = $customer->invoices->whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('total_amount - received_amount'));
        
        // Get latest invoices and payments
        $recentInvoices = $customer->invoices()->latest()->take(5)->get();
        $recentPayments = $customer->payments()->latest()->take(5)->get();

        return view('admin.customers.profile', compact(
            'customer', 
            'totalInvoices', 
            'totalPaid', 
            'totalDue',
            'recentInvoices',
            'recentPayments'
        ));
    }

    public function edit($id)
    {
        $customer = CustomerModel::with(['user', 'customerproducts.product'])->findOrFail($id);
        
        $regularproducts = product::whereHas('type', function($query) {
            $query->where('name', 'regular');
        })->get();
        
        $specialproducts = product::whereHas('type', function($query) {
            $query->where('name', 'special');
        })->get();
        
        return view('admin.customers.edit', compact('customer', 'regularproducts', 'specialproducts'));
    }

    public function update(Request $request, $id)
    {
        $customer = CustomerModel::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->user_id,
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:500',
            'connection_address' => 'nullable|string|max:500',
            'id_type' => 'nullable|string|in:NID,Passport,Driving License',
            'id_number' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Update user
            $customer->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update customer
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'connection_address' => $request->connection_address,
                'id_type' => $request->id_type,
                'id_number' => $request->id_number,
                'is_active' => $request->has('is_active') ? $request->is_active : $customer->is_active,
            ]);

            DB::commit();

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating customer: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $customer = CustomerModel::with(['user', 'invoices', 'payments'])->findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if customer has invoices or payments
            if ($customer->invoices->count() > 0 || $customer->payments->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete customer with existing invoices or payments. Please delete related records first.');
            }

            // Remove products first
            $customer->customerproducts()->delete();
            
            // Delete user account first
            if ($customer->user) {
                $customer->user->delete();
            }
            
            // Then delete customer profile
            $customer->delete();

            DB::commit();

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }

    // ========== CUSTOMER STATUS MANAGEMENT ==========

    public function toggleStatus($id)
    {
        $customer = CustomerModel::findOrFail($id);
        
        try {
            $customer->update(['is_active' => !$customer->is_active]);
            
            $status = $customer->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Customer {$status} successfully!");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating customer status: ' . $e->getMessage());
        }
    }

    // ========== BILLING HISTORY METHOD ==========
    
    public function billingHistory($id)
    {
        $customer = CustomerModel::with(['invoices'])->findOrFail($id);
        $invoices = $customer->invoices()->latest()->paginate(10);

        return view('admin.customers.billing-history', compact('customer', 'invoices'));
    }

    // ========== CUSTOMER PROFILE (FOR CUSTOMER PORTAL) ==========
    
    public function profile()
    {
        $user = Auth::user();
        $customer = CustomerModel::where('user_id', $user->id)
            ->with(['invoices', 'payments', 'customerproducts.product'])
            ->firstOrFail();

        // Calculate statistics for customer portal
        $totalInvoices = $customer->invoices->count();
        $paidInvoices = $customer->invoices->where('status', 'paid')->count();
        $pendingInvoices = $customer->invoices->whereIn('status', ['unpaid', 'partial'])->count();
        $totalDue = $customer->invoices->whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('total_amount - received_amount'));

        // Get recent activity
        $recentInvoices = $customer->invoices()->latest()->take(5)->get();
        $recentPayments = $customer->payments()->latest()->take(5)->get();

        return view('customer.profile', compact(
            'customer',
            'totalInvoices',
            'paidInvoices',
            'pendingInvoices',
            'totalDue',
            'recentInvoices',
            'recentPayments'
        ));
    }

    // ========== product MANAGEMENT METHODS ==========

    public function addproduct(Request $request, $id)
    {
        $customer = CustomerModel::findOrFail($id);
        
        $request->validate([
            'product_id' => 'required|exists:products,p_id'
        ]);

        try {
            $pkg = product::findOrFail($request->product_id);
            $customer->assignproduct(
                $pkg->p_id,
                $pkg->monthly_price,
                1, // billingCycleMonths
                'active'
            );
            return redirect()->back()->with('success', 'product added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding product: ' . $e->getMessage());
        }
    }

    public function removeproduct(Request $request, $id)
    {
        $customer = CustomerModel::findOrFail($id);
        
        $request->validate([
            'pivot_id' => 'required|exists:customer_to_products,cp_id'
        ]);

        try {
            DB::table('customer_to_products')->where('cp_id', $request->pivot_id)->delete();
            return redirect()->back()->with('success', 'product removed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error removing product: ' . $e->getMessage());
        }
    }

    // ========== DEBUG METHOD ==========
    
    public function debug()
    {
        echo "<h3>Debug Customers</h3>";
        
        // Check customers from customers table
        $customersFromCustomerTable = CustomerModel::with(['user', 'customerproducts'])->get();
        echo "<h4>From Customer Table:</h4>";
        
        if ($customersFromCustomerTable->count() === 0) {
            echo "❌ No customers found in customers table!<br>";
        } else {
            foreach ($customersFromCustomerTable as $cust) {
                echo "Customer ID: " . $cust->c_id . "<br>";
                echo "Customer Code: " . ($cust->customer_id ?? 'NULL') . "<br>";
                echo "User ID: " . $cust->user_id . "<br>";
                echo "Name: " . $cust->name . "<br>";
                echo "Email: " . $cust->email . "<br>";
                echo "Phone: " . ($cust->phone ?? 'NULL') . "<br>";
                echo "products Count: " . $cust->customerproducts->count() . "<br>";
                echo "Active: " . ($cust->is_active ? 'YES' : 'NO') . "<br>";
                echo "User exists: " . ($cust->user ? 'YES' : 'NO') . "<br>";
                if ($cust->user) {
                    echo "User Role: " . $cust->user->role . "<br>";
                }
                echo "<hr>";
            }
        }
        
        // Check users from users table
        $usersFromUserTable = User::all();
        echo "<h4>All Users in User Table:</h4>";
        
        if ($usersFromUserTable->count() === 0) {
            echo "❌ No users found in users table!<br>";
        } else {
            foreach ($usersFromUserTable as $user) {
                echo "User ID: " . $user->id . "<br>";
                echo "User Name: " . $user->name . "<br>";
                echo "User Email: " . $user->email . "<br>";
                echo "User Role: " . ($user->role ?? 'NOT SET') . "<br>";
                echo "Created: " . $user->created_at . "<br>";
                echo "<hr>";
            }
        }

        // Check products
        $products = product::all();
        echo "<h4>Available products:</h4>";
        
        if ($products->count() === 0) {
            echo "❌ No products found!<br>";
        } else {
            foreach ($products as $product) {
                echo "product ID: " . $product->p_id . "<br>";
                echo "product Name: " . $product->name . "<br>";
                echo "product Type: " . $product->product_type . "<br>";
                echo "Monthly Price: ৳" . $product->monthly_price . "<br>";
                echo "<hr>";
            }
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('type')
            ->orderBy('created_at', 'desc') // Latest packages first
            ->orderBy('p_id', 'desc') // Then by ID descending
            ->get();
        $packageTypes = PackageType::all();
        $stats = $this->getPackageStats();

        return view('admin.packages.index', compact('packages', 'stats', 'packageTypes'));
    }

    public function create()
    {
        $packageTypes = PackageType::all();
        return view('admin.packages.create', compact('packageTypes'));
    }

    public function store(Request $request)
    {
        Log::info('Package creation request received', [
            'method' => $request->method(),
            'url' => $request->url(),
            'all_data' => $request->all(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'x_requested_with' => $request->header('X-Requested-With'),
        ]);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:120',
            'package_type_id' => 'required|exists:package_types,id',
            'description' => 'required|string',
            'monthly_price' => 'required|numeric|min:0',
        ]);
        
        Log::info('Package validation passed', $validatedData);

        try {
            // Remove created_at and updated_at from the data since Laravel handles them automatically
            $packageData = [
                'name' => $validatedData['name'],
                'package_type_id' => $validatedData['package_type_id'],
                'description' => $validatedData['description'],
                'monthly_price' => $validatedData['monthly_price'],
            ];
            
            Log::info('Creating package with data', $packageData);
            
            $package = Package::create($packageData);
            
            Log::info('Package created successfully', ['package_id' => $package->p_id]);

            return response()->json([
                'success' => true,
                'message' => 'Package created successfully!',
                'package' => $package
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create package: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create package: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        Log::info('=== SHOW METHOD CALLED ===', [
            'id' => $id,
            'type' => gettype($id),
            'request_url' => request()->url(),
            'request_method' => request()->method()
        ]);
        
        try {
            Log::info('Fetching package', ['id' => $id]);
            
            $package = Package::with('type')->where('p_id', $id)->firstOrFail();
            
            Log::info('Package found', ['package' => $package->toArray()]);
            
            return response()->json($package);
        } catch (\Exception $e) {
            Log::error('Failed to fetch package', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Package not found: ' . $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Package update request received', [
            'method' => $request->method(),
            'url' => $request->url(),
            'package_id' => $id,
            'all_data' => $request->all(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'x_requested_with' => $request->header('X-Requested-With'),
        ]);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:120',
            'package_type_id' => 'required|exists:package_types,id',
            'description' => 'required|string',
            'monthly_price' => 'required|numeric|min:0',
        ]);
        
        Log::info('Package update validation passed', $validatedData);

        try {
            $package = Package::where('p_id', $id)->firstOrFail();
            
            // Remove updated_at from the data since Laravel handles it automatically
            $packageData = [
                'name' => $validatedData['name'],
                'package_type_id' => $validatedData['package_type_id'],
                'description' => $validatedData['description'],
                'monthly_price' => $validatedData['monthly_price'],
            ];
            
            Log::info('Updating package with data', $packageData);
            
            $package->update($packageData);
            
            Log::info('Package updated successfully', ['package_id' => $package->p_id]);

            return response()->json([
                'success' => true,
                'message' => 'Package updated successfully!',
                'package' => $package
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update package: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update package: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Deleting package', ['id' => $id]);
            
            $package = Package::where('p_id', $id)->firstOrFail();

            $assignedCount = DB::table('customer_to_packages')
                ->where('p_id', $id)
                ->where('status', 'active')
                ->count();

            if ($assignedCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete package. It is currently assigned to ' . $assignedCount . ' active customer(s).'
                ], 400);
            }

            $package->delete();
            
            Log::info('Package deleted successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete package', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete package: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getPackageStats()
    {
        $totalCustomers = DB::table('customer_to_packages')
            ->where('status', 'active')
            ->count();

        return [
            'total_packages' => Package::count(),
            'active_customers' => $totalCustomers,
            'average_price' => Package::avg('monthly_price') ?? 0,
            'most_popular_package' => $this->getMostPopularPackage()
        ];
    }

    private function getMostPopularPackage()
    {
        $popularPackage = DB::table('customer_to_packages as cp')
            ->join('packages as p', 'cp.p_id', '=', 'p.p_id')
            ->where('cp.status', 'active')
            ->select('p.p_id', 'p.name', DB::raw('COUNT(cp.cp_id) as customer_count'))
            ->groupBy('p.p_id', 'p.name')
            ->orderByDesc('customer_count')
            ->first();

        return $popularPackage ?: null;
    }

    // -------------------------
    // Package Type Management
    // -------------------------

    // Update your package type methods in PackageController
    public function packageTypes()
    {
        $packageTypes = PackageType::withCount('packages')->orderBy('name')->get();
        
        // Calculate package counts for each type
        $packageCounts = [];
        foreach ($packageTypes as $type) {
            $packageCounts[$type->name] = $type->packages_count;
        }

        return view('admin.packages.types', compact('packageTypes', 'packageCounts'));
    }

    public function addPackageType(Request $request)
    {
        // Debug the incoming request
        Log::info('Add Package Type Request:', [
            'method' => $request->method(),
            'url' => $request->url(),
            'all_data' => $request->all(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'x_requested_with' => $request->header('X-Requested-With'),
        ]);
        
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:50|unique:package_types,name',
            ]);
            
            Log::info('Package type validation passed', $validatedData);

            Log::info('Creating package type: ' . $validatedData['name']);
            
            $type = PackageType::create([
                'name' => $validatedData['name'],
            ]);

            Log::info('Package type created successfully: ' . $type->id);

            return response()->json([
                'success' => true,
                'message' => 'Package type added successfully!',
                'type' => $type
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Package type validation failed: ' . $e->getMessage(), [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to create package type: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add package type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePackageType($id)
    {
        try {
            $type = PackageType::findOrFail($id);

            // Check if this is a protected type
            if (in_array($type->name, ['regular', 'special'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete protected package types (regular, special).'
                ], 400);
            }

            // Delete packages belonging to this type
            $type->packages()->delete();
            $type->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package type and associated packages deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete package type: ' . $e->getMessage()
            ], 500);
        }
    }
}
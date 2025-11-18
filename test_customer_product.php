<?php
require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\CustomerProduct;

// Create a new capsule instance
$capsule = new Capsule;

// Add a connection
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'billing',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods
$capsule->setAsGlobal();

// Setup the Eloquent ORM
$capsule->bootEloquent();

try {
    echo "Testing CustomerProduct model...\n";
    
    // Create a test customer product
    $customerProduct = new CustomerProduct();
    $customerProduct->c_id = 1;
    $customerProduct->p_id = 1;
    $customerProduct->assign_date = date('Y-m-d');
    $customerProduct->billing_cycle_months = 1;
    $customerProduct->status = 'active';
    $customerProduct->is_active = true;
    $customerProduct->save();
    
    echo "CustomerProduct created successfully with ID: " . $customerProduct->cp_id . "\n";
    
    // Test retrieval
    $retrievedCustomerProduct = CustomerProduct::find($customerProduct->cp_id);
    echo "Retrieved CustomerProduct with customer ID: " . $retrievedCustomerProduct->c_id . "\n";
    
    // Clean up
    $retrievedCustomerProduct->delete();
    echo "CustomerProduct deleted successfully\n";
    
    echo "All CustomerProduct tests passed!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error in file: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}
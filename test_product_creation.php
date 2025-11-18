<?php
require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Product;

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
    echo "Creating test product...\n";
    
    $product = new Product();
    $product->name = 'Test Product';
    $product->product_type_id = 1;
    $product->monthly_price = 100;
    $product->save();
    
    echo "Product created successfully with ID: " . $product->p_id . "\n";
    
    // Test retrieval
    $retrievedProduct = Product::find($product->p_id);
    echo "Retrieved product: " . $retrievedProduct->name . "\n";
    
    // Clean up
    $retrievedProduct->delete();
    echo "Product deleted successfully\n";
    
    echo "All tests passed!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error in file: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}
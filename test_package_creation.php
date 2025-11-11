<?php
require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Create a service container
$container = new Container();

// Create a database capsule
$capsule = new Capsule($container);
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

// Test package creation
try {
    $package = new App\Models\Package();
    $package->name = 'Test Package';
    $package->package_type_id = 1;
    $package->description = 'Test Description';
    $package->monthly_price = 100;
    $package->save();
    
    echo "Package created successfully!\n";
    print_r($package->toArray());
} catch (Exception $e) {
    echo "Error creating package: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
<?php

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Create a service container
$container = new Container();

// Create a new app instance
$app = new \Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Bootstrap the application
$app->bootstrapWith([
    \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
    \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
    \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
    \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
    \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
    \Illuminate\Foundation\Bootstrap\BootProviders::class,
]);

// Test the relationship
try {
    $product = \App\Models\Product::first();
    echo "Product: " . $product->name . "\n";
    echo "Type: " . $product->type->name . "\n";
    echo "Test successful!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
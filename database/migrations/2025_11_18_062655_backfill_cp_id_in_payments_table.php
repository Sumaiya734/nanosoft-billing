<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\CustomerProduct;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill cp_id for existing payments
        // Strategy: For each payment, find the first active customer product for that customer
        
        $payments = DB::table('payments')->whereNull('cp_id')->get();
        
        foreach ($payments as $payment) {
            // Find the first active customer product for this customer
            $customerProduct = DB::table('customer_to_products')
                ->where('c_id', $payment->c_id)
                ->where('is_active', true)
                ->where('status', 'active')
                ->orderBy('assign_date', 'asc')
                ->first();
            
            if ($customerProduct) {
                DB::table('payments')
                    ->where('payment_id', $payment->payment_id)
                    ->update(['cp_id' => $customerProduct->cp_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set cp_id back to null for backfilled records
        DB::table('payments')->update(['cp_id' => null]);
    }
};

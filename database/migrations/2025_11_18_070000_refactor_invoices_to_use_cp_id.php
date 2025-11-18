<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add the cp_id column
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedInteger('cp_id')->nullable()->after('c_id');
            $table->index('cp_id', 'idx_invoices_customer_product');
        });

        // Migrate existing data: Try to find matching cp_id for each invoice
        // This assumes invoices were for the most recent product assignment
        DB::statement("
            UPDATE invoices i
            INNER JOIN (
                SELECT cp.cp_id, cp.c_id, 
                       ROW_NUMBER() OVER (PARTITION BY cp.c_id ORDER BY cp.assign_date DESC) as rn
                FROM customer_to_products cp
            ) cp ON i.c_id = cp.c_id AND cp.rn = 1
            SET i.cp_id = cp.cp_id
            WHERE i.cp_id IS NULL
        ");

        // Make cp_id NOT NULL after migration
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedInteger('cp_id')->nullable(false)->change();
        });

        // Add foreign key constraint
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('cp_id', 'fk_invoices_customer_product')
                  ->references('cp_id')
                  ->on('customer_to_products')
                  ->onDelete('cascade');
        });

        // Drop the old c_id column and its index
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_customer');
            $table->dropColumn('c_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add c_id column
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedInteger('c_id')->nullable()->after('invoice_number');
            $table->index('c_id', 'idx_invoices_customer');
        });

        // Restore c_id from cp_id relationship
        DB::statement("
            UPDATE invoices i
            INNER JOIN customer_to_products cp ON i.cp_id = cp.cp_id
            SET i.c_id = cp.c_id
        ");

        // Make c_id NOT NULL
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedInteger('c_id')->nullable(false)->change();
        });

        // Drop cp_id
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('fk_invoices_customer_product');
            $table->dropIndex('idx_invoices_customer_product');
            $table->dropColumn('cp_id');
        });
    }
};

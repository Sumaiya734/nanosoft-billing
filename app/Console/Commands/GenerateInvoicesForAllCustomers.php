<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\CustomerProduct;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateInvoicesForAllCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-all {--month= : Specific month to generate invoices for (YYYY-MM format)} {--force : Force generation even if invoices already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate invoices with unique invoice numbers for all customers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $month = $this->option('month') ?? date('Y-m');
        $force = $this->option('force');
        
        $this->info("Generating invoices for all customers for month: {$month}");
        
        try {
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            $displayMonth = $monthDate->format('F Y');
        } catch (\Exception $e) {
            $this->error("Invalid month format. Please use YYYY-MM format.");
            return 1;
        }
        
        // Get all active customers with active products
        $customers = $this->getAllActiveCustomersWithProducts($monthDate);
        
        if ($customers->isEmpty()) {
            $this->info("No active customers with products found for {$displayMonth}.");
            return 0;
        }
        
        $this->info("Found {$customers->count()} active customer products.");
        
        $generatedCount = 0;
        $skippedCount = 0;
        
        foreach ($customers as $customerProduct) {
            try {
                // Check if invoice already exists for this customer product and month
                $existingInvoice = Invoice::where('cp_id', $customerProduct->cp_id)
                    ->whereYear('issue_date', $monthDate->year)
                    ->whereMonth('issue_date', $monthDate->month)
                    ->first();
                
                if ($existingInvoice && !$force) {
                    $this->line("Skipping customer {$customerProduct->customer->name} - invoice already exists ({$existingInvoice->invoice_number})");
                    $skippedCount++;
                    continue;
                }
                
                if ($existingInvoice && $force) {
                    $this->line("Deleting existing invoice {$existingInvoice->invoice_number} for customer {$customerProduct->customer->name}");
                    $existingInvoice->delete();
                }
                
                // Create new invoice
                $invoice = $this->createCustomerInvoice($customerProduct, $monthDate);
                
                if ($invoice) {
                    $this->line("Generated invoice {$invoice->invoice_number} for customer {$customerProduct->customer->name}");
                    $generatedCount++;
                } else {
                    $this->error("Failed to generate invoice for customer {$customerProduct->customer->name}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to generate invoice for customer product {$customerProduct->cp_id}: " . $e->getMessage());
                Log::error("Invoice generation failed for customer product {$customerProduct->cp_id}: " . $e->getMessage());
            }
        }
        
        $this->info("Invoice generation complete!");
        $this->info("Generated: {$generatedCount} invoices");
        $this->info("Skipped: {$skippedCount} customers (already had invoices)");
        
        return 0;
    }
    
    /**
     * Get all active customers with active products
     */
    private function getAllActiveCustomersWithProducts(Carbon $monthDate)
    {
        return CustomerProduct::with(['customer', 'product'])
            ->where('status', 'active')
            ->where('is_active', 1)
            ->whereHas('customer', function($q) {
                $q->where('is_active', 1);
            })
            ->where('assign_date', '<=', $monthDate->endOfMonth())
            ->get();
    }
    
    /**
     * Create invoice for a customer product
     */
    private function createCustomerInvoice($customerProduct, Carbon $monthDate)
    {
        try {
            // Calculate product amount from monthly_price and billing_cycle
            $productAmount = $customerProduct->product->monthly_price * $customerProduct->billing_cycle_months;
            
            // Subtotal defaults to calculated amount but can be overridden
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
            
            // Generate notes
            $notes = 'Auto-generated invoice for ' . $monthDate->format('F Y');
            $notes .= ' - Product: ' . $customerProduct->product->name;
            if ($previousDue > 0) {
                $notes .= " (Includes ৳" . number_format($previousDue, 2) . " previous due)";
            }
            
            // Create the invoice - Invoice number will be auto-generated by the model
            $invoice = Invoice::create([
                'cp_id' => $customerProduct->cp_id,
                'issue_date' => $monthDate->format('Y-m-d'),
                'previous_due' => $previousDue,
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'received_amount' => 0,
                'next_due' => $totalAmount,
                'status' => 'unpaid',
                'notes' => $notes,
                'created_by' => 1 // System generated
            ]);
            
            Log::info("Auto-generated invoice {$invoice->invoice_number} for customer {$customerProduct->customer->name} with amount ৳{$totalAmount}");
            
            return $invoice;
        } catch (\Exception $e) {
            Log::error('Failed to create invoice for customer product ' . $customerProduct->cp_id . ': ' . $e->getMessage());
            return null;
        }
    }
}
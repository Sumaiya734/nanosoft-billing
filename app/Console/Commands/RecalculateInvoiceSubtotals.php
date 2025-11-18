<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class RecalculateInvoiceSubtotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:recalculate-subtotals {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate invoice subtotals based on product prices and billing cycles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('Running in DRY-RUN mode - no changes will be made');
        } else {
            $this->warn('This will update existing invoices. Press Ctrl+C to cancel...');
            sleep(3);
        }

        $invoices = Invoice::with('customerProduct.product')->get();
        
        $this->info("Found {$invoices->count()} invoices to process");
        
        $updatedCount = 0;
        $skippedCount = 0;
        $errors = [];
        
        foreach ($invoices as $invoice) {
            try {
                if (!$invoice->customerProduct || !$invoice->customerProduct->product) {
                    $this->line("Skipping {$invoice->invoice_number} - No product found");
                    $skippedCount++;
                    continue;
                }
                
                // Calculate correct subtotal
                $monthlyPrice = (float) $invoice->customerProduct->product->monthly_price;
                $billingCycle = (int) $invoice->customerProduct->billing_cycle_months;
                $correctSubtotal = $monthlyPrice * $billingCycle;
                
                // Get current values
                $currentSubtotal = (float) $invoice->subtotal;
                $previousDue = (float) $invoice->previous_due;
                
                // Calculate correct total_amount
                $correctTotalAmount = $correctSubtotal + $previousDue;
                
                // Check if update is needed
                if (abs($currentSubtotal - $correctSubtotal) > 0.01) {
                    $this->line("Invoice {$invoice->invoice_number}:");
                    $this->line("  Current Subtotal: ৳{$currentSubtotal}");
                    $this->line("  Correct Subtotal: ৳{$correctSubtotal}");
                    $this->line("  Previous Due: ৳{$previousDue}");
                    $this->line("  New Total: ৳{$correctTotalAmount}");
                    
                    if (!$dryRun) {
                        // Update the invoice
                        $invoice->update([
                            'subtotal' => $correctSubtotal,
                            'total_amount' => $correctTotalAmount,
                            // Recalculate next_due
                            'next_due' => max(0, $correctTotalAmount - $invoice->received_amount)
                        ]);
                        
                        $this->info("  ✓ Updated");
                    } else {
                        $this->info("  Would update (dry-run)");
                    }
                    
                    $updatedCount++;
                } else {
                    $skippedCount++;
                }
                
            } catch (\Exception $e) {
                $error = "Error processing {$invoice->invoice_number}: " . $e->getMessage();
                $this->error($error);
                $errors[] = $error;
            }
        }
        
        $this->info("\n=== Summary ===");
        $this->info("Total Invoices: {$invoices->count()}");
        $this->info("Updated: {$updatedCount}");
        $this->info("Skipped: {$skippedCount}");
        
        if (count($errors) > 0) {
            $this->error("Errors: " . count($errors));
        }
        
        if ($dryRun) {
            $this->warn("\nThis was a DRY-RUN. Run without --dry-run to apply changes.");
        } else {
            $this->info("\nRecalculation complete!");
        }
        
        return 0;
    }
}

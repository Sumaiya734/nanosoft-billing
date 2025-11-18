// Payment Modal Functions
class PaymentModal {
    constructor() {
        this.isSubmitting = false;
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Handle payment modal show event
        $(document).on('show.bs.modal', '#addPaymentModal', (event) => {
            this.handleModalShow(event);
        });

        // Payment amount validation
        $(document).on('input', '#payment_amount', (e) => {
            this.validatePaymentAmount(e.target);
            this.calculateReceivedAndDue(); // Calculate received amount and next due
        });

        // Payment form submission
        $(document).on('submit', '#addPaymentForm', (e) => {
            this.handlePaymentSubmit(e);
        });

        // Reset payment form when modal is hidden
        $(document).on('hidden.bs.modal', '#addPaymentModal', () => {
            this.resetPaymentForm();
        });
    }

    handleModalShow(event) {
        const button = $(event.relatedTarget);
        const invoiceId = button.data('invoice-id');
        
        console.log('Loading payment data for invoice ID:', invoiceId);
        console.log('Button data:', button.data());
        
        // Check if we have the essential data from the button
        if (button.data('invoice-number') && button.data('customer-name')) {
            // Use button data directly for immediate display
            this.populateFromButtonData(button);
        } else if (invoiceId) {
            // Fetch from database if we only have invoice ID
            this.setLoadingState();
            this.fetchInvoiceData(invoiceId)
                .then(invoice => {
                    console.log('Invoice data loaded from database:', invoice);
                    this.populateModal(invoiceId, invoice);
                })
                .catch(error => {
                    console.error('Error fetching invoice data from database:', error);
                    this.showToast('Error loading invoice data from database. Please try again.', 'error');
                    this.fallbackToButtonData(button);
                });
        } else {
            this.setLoadingState();
            this.showToast('Missing invoice data. Please try again.', 'error');
        }
    }

    setLoadingState() {
        $('#payment_invoice_number_display').text('Loading...');
        $('#payment_customer_name_display').text('Loading...');
        $('#payment_customer_email_display').text('Loading...');
        $('#payment_customer_phone_display').text('Loading...');
        $('#payment_total_amount_display').text('৳ 0.00');
        $('#payment_due_amount_display').text('৳ 0.00');
        $('#payment_received_amount_display').text('৳ 0.00');
        $('#payment_status_display').text('Loading...');
        $('#payment_status_display').removeClass().addClass('badge bg-secondary');
    }

    async fetchInvoiceData(invoiceId) {
        // Get the base URL from the current page
        const baseUrl = document.querySelector('meta[name="base-url"]')?.content || window.location.origin;
        const response = await fetch(`${baseUrl}/admin/billing/invoice/${invoiceId}/data`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        if (data.success) {
            return data.invoice;
        } else {
            throw new Error(data.message || 'Failed to fetch invoice data');
        }
    }

    populateFromButtonData(button) {
        const invoiceId = button.data('invoice-id');
        const invoiceNumber = button.data('invoice-number');
        const customerName = button.data('customer-name');
        const customerEmail = button.data('customer-email');
        const customerPhone = button.data('customer-phone');
        const totalAmount = button.data('total-amount');
        const dueAmount = button.data('due-amount');
        const receivedAmount = button.data('received-amount');
        const status = button.data('status');
        
        // Product-specific data
        const cpId = button.data('cp-id');
        const productName = button.data('product-name');
        const productPrice = button.data('product-price');
        const billingCycle = button.data('billing-cycle') || 1;
        const productAmount = button.data('product-amount');
        
        console.log('Populating from button data:', {
            invoiceId, invoiceNumber, customerName, customerEmail, customerPhone,
            totalAmount, dueAmount, receivedAmount, status,
            cpId, productName, productPrice, billingCycle, productAmount
        });
        
        // Set form action and invoice ID
        if (invoiceId) {
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || window.location.origin;
            let fullPath = `${baseUrl}/admin/billing/record-payment/${invoiceId}`;
            fullPath = fullPath.replace(/(\/netbill-bd\/public){2,}/g, '/netbill-bd/public');
            
            $('#addPaymentForm').attr('action', fullPath);
            $('#payment_invoice_id').val(invoiceId);
            $('#payment_cp_id').val(cpId);
        }
        
        // Populate invoice and customer fields
        $('#payment_invoice_number_display').text(invoiceNumber || 'N/A');
        $('#payment_customer_name_display').text(customerName || 'N/A');
        $('#payment_customer_email_display').text(customerEmail || 'N/A');
        $('#payment_customer_phone_display').text(customerPhone || 'N/A');
        $('#payment_total_amount_display').text('৳ ' + (parseFloat(totalAmount) || 0).toLocaleString('en-BD', {minimumFractionDigits: 2}));
        $('#payment_due_amount_display').text('৳ ' + (parseFloat(dueAmount) || 0).toLocaleString('en-BD', {minimumFractionDigits: 2}));
        $('#payment_received_amount_display').text('৳ ' + (parseFloat(receivedAmount) || 0).toLocaleString('en-BD', {minimumFractionDigits: 2}));
        
        // Populate product-specific fields
        $('#payment_product_name').text(productName || 'Unknown Product');
        $('#payment_product_price').text('৳ ' + (parseFloat(productPrice) || 0).toLocaleString('en-BD', {minimumFractionDigits: 2}));
        $('#payment_billing_cycle').text(billingCycle > 1 ? `${billingCycle} months` : 'Monthly');
        $('#payment_product_amount').text('৳ ' + (parseFloat(productAmount) || 0).toLocaleString('en-BD', {minimumFractionDigits: 2}));
        
        // Set status badge
        const statusDisplay = $('#payment_status_display');
        const statusText = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'N/A';
        statusDisplay.text(statusText);
        statusDisplay.removeClass().addClass('badge');
        
        switch(status) {
            case 'paid':
                statusDisplay.addClass('bg-success');
                break;
            case 'partial':
                statusDisplay.addClass('bg-warning text-dark');
                break;
            case 'unpaid':
                statusDisplay.addClass('bg-danger');
                break;
            default:
                statusDisplay.addClass('bg-secondary');
        }
        
        // Set payment amount to product amount by default (for this specific product)
        const paymentAmountField = $('#payment_amount');
        const prodAmt = parseFloat(productAmount) || 0;
        paymentAmountField.val(prodAmt.toFixed(2));
        paymentAmountField.attr('max', prodAmt);
        paymentAmountField.attr('min', 0.01);
        
        // Update max amount display
        $('#payment_max_amount').text('৳ ' + prodAmt.toLocaleString('en-BD', {minimumFractionDigits: 2}));
        
        // Reset validation
        paymentAmountField.removeClass('is-invalid');
        $('#payment_amount_error').hide();
        
        console.log('Payment modal populated from button data (product-specific)');
    }

    populateModal(invoiceId, invoice) {
        // Set the form action with invoice ID
        // Get the base URL from meta tag or current origin
        const baseUrl = document.querySelector('meta[name="base-url"]')?.content || window.location.origin;
        
        // Ensure we have the correct path without duplication
        let fullPath = `${baseUrl}/admin/billing/record-payment/${invoiceId}`;
        // Remove any duplicate paths
        fullPath = fullPath.replace(/(\/netbill-bd\/public){2,}/g, '/netbill-bd/public');
        
        $('#addPaymentForm').attr('action', fullPath);
        $('#payment_invoice_id').val(invoiceId);

        // Format currency values
        const totalAmount = parseFloat(invoice.total_amount) || 0;
        const dueAmount = parseFloat(invoice.next_due) || 0;
        const receivedAmount = parseFloat(invoice.received_amount) || 0;
        
        // Populate modal fields with REAL database data
        $('#payment_invoice_number_display').text(invoice.invoice_number || 'N/A');
        $('#payment_customer_name_display').text(invoice.customer.name || 'N/A');
        $('#payment_customer_email_display').text(invoice.customer.email || 'N/A');
        $('#payment_customer_phone_display').text(invoice.customer.phone || 'N/A');
        $('#payment_total_amount_display').text('৳ ' + totalAmount.toLocaleString('en-BD', {minimumFractionDigits: 2}));
        $('#payment_due_amount_display').text('৳ ' + dueAmount.toLocaleString('en-BD', {minimumFractionDigits: 2}));
        $('#payment_received_amount_display').text('৳ ' + receivedAmount.toLocaleString('en-BD', {minimumFractionDigits: 2}));
        
        // Set status badge with appropriate color
        const statusDisplay = $('#payment_status_display');
        const statusText = invoice.status ? invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1) : 'N/A';
        statusDisplay.text(statusText);
        statusDisplay.removeClass().addClass('badge');
        
        switch(invoice.status) {
            case 'paid':
                statusDisplay.addClass('bg-success');
                break;
            case 'partial':
                statusDisplay.addClass('bg-warning text-dark');
                break;
            case 'unpaid':
                statusDisplay.addClass('bg-danger');
                break;
            default:
                statusDisplay.addClass('bg-secondary');
        }
        
        // Set payment amount to due amount by default
        const paymentAmountField = $('#payment_amount');
        paymentAmountField.val(dueAmount.toFixed(2));
        paymentAmountField.attr('max', dueAmount);
        paymentAmountField.attr('min', 0.01);
        
        // Update max amount display
        $('#payment_max_amount').text('৳ ' + dueAmount.toLocaleString('en-BD', {minimumFractionDigits: 2}));
        
        // Reset validation
        paymentAmountField.removeClass('is-invalid');
        $('#payment_amount_error').hide();
        
        // Calculate initial received amount and next due
        this.calculateReceivedAndDue();
        
        // Display products
        this.displayProducts(invoice);
        
        console.log('Payment modal populated with database data');
    }

    fallbackToButtonData(button) {
        this.populateFromButtonData(button);
    }

    validatePaymentAmount(input) {
        const paymentAmount = parseFloat($(input).val()) || 0;
        const dueAmountText = $('#payment_due_amount_display').text();
        const dueAmount = parseFloat(dueAmountText.replace(/[^\d.]/g, '')) || 0;
        
        if (paymentAmount > dueAmount) {
            $(input).addClass('is-invalid');
            $('#payment_amount_error').show();
        } else {
            $(input).removeClass('is-invalid');
            $('#payment_amount_error').hide();
        }
    }

    async fetchAndDisplayProducts(invoiceId) {
        const productsContainer = $('#payment_products_display');
        productsContainer.html('<div class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div> Loading products...</div>');
        
        try {
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || window.location.origin;
            const response = await fetch(`${baseUrl}/admin/billing/invoice/${invoiceId}/data`);
            
            if (!response.ok) {
                throw new Error('Failed to fetch invoice data');
            }
            
            const data = await response.json();
            
            if (data.success && data.invoice) {
                this.displayProducts(data.invoice);
            } else {
                productsContainer.html('<p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i>No product information available</p>');
            }
        } catch (error) {
            console.error('Error fetching products:', error);
            productsContainer.html('<p class="text-warning mb-0"><i class="fas fa-exclamation-triangle me-1"></i>Could not load products</p>');
        }
    }

    displayProducts(invoice) {
        const productsContainer = $('#payment_products_display');
        
        // Check if invoice has customer with products
        if (invoice.customer && invoice.customer.customerproducts && invoice.customer.customerproducts.length > 0) {
            let productsHtml = '<div class="row g-2">';
            
            invoice.customer.customerproducts.forEach((customerProduct, index) => {
                if (customerProduct.product) {
                    const product = customerProduct.product;
                    const monthlyPrice = parseFloat(product.monthly_price) || 0;
                    const billingCycle = customerProduct.billing_cycle_months || 1;
                    const totalPrice = monthlyPrice * billingCycle;
                    
                    productsHtml += `
                        <div class="col-12 col-md-6">
                            <div class="product-item p-2 border rounded bg-light">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark">
                                            <i class="fas fa-box-open me-1 text-primary"></i>
                                            ${product.name || 'Unknown Product'}
                                        </h6>
                                        <div class="text-muted small">
                                            <div>৳${monthlyPrice.toLocaleString('en-BD', {minimumFractionDigits: 2})}/month</div>
                                            ${billingCycle > 1 ? `<div><span class="badge bg-info text-white">×${billingCycle} months cycle</span></div>` : ''}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <strong class="text-success">৳${totalPrice.toLocaleString('en-BD', {minimumFractionDigits: 2})}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            
            productsHtml += '</div>';
            productsContainer.html(productsHtml);
        } else {
            productsContainer.html('<p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i>No products assigned to this invoice</p>');
        }
    }

    calculateReceivedAndDue() {
        // Get values
        const paymentAmount = parseFloat($('#payment_amount').val()) || 0;
        const dueAmountText = $('#payment_due_amount_display').text();
        const dueAmount = parseFloat(dueAmountText.replace(/[^\d.]/g, '')) || 0;
        const receivedAmountText = $('#payment_received_amount_display').text();
        const currentReceivedAmount = parseFloat(receivedAmountText.replace(/[^\d.]/g, '')) || 0;
        
        // Calculate new values
        const newReceivedAmount = currentReceivedAmount + paymentAmount;
        const nextDue = Math.max(0, dueAmount - paymentAmount);
        
        // Update the input fields
        $('#received_amount').val(newReceivedAmount.toFixed(2));
        $('#next_due').val(nextDue.toFixed(2));
    }

    validatePaymentForm() {
        const paymentAmount = parseFloat($('#payment_amount').val()) || 0;
        const dueAmountText = $('#payment_due_amount_display').text();
        const dueAmount = parseFloat(dueAmountText.replace(/[^\d.]/g, '')) || 0;
        
        if (paymentAmount > dueAmount) {
            this.showToast('Payment amount cannot exceed due amount!', 'error');
            return false;
        }
        
        if (paymentAmount <= 0) {
            this.showToast('Payment amount must be greater than 0!', 'error');
            return false;
        }

        return true;
    }

    async handlePaymentSubmit(e) {
        e.preventDefault();
        
        // Prevent duplicate submissions
        if (this.isSubmitting) {
            console.log('Form already submitting, ignoring duplicate submission');
            return;
        }
        
        const form = e.target;
        const submitBtn = $(form).find('button[type="submit"]');
        
        // Double-check button state
        if (submitBtn.prop('disabled')) {
            return;
        }
        
        const formData = new FormData(form);
        const originalText = submitBtn.html();
        
        // Validate amount
        if (!this.validatePaymentForm()) {
            return;
        }

        // Set submission flag and disable button immediately
        this.isSubmitting = true;
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...');
        submitBtn.prop('disabled', true);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            
            if (data.success) {
                this.showToast(data.message || 'Payment recorded successfully!', 'success');
                $('#addPaymentModal').modal('hide');
                // Reload page to show updated data
                setTimeout(() => location.reload(), 1500);
            } else {
                this.showToast(data.message || 'Error recording payment!', 'error');
                this.isSubmitting = false;
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        } catch (error) {
            console.error('Error:', error);
            this.showToast('Error processing payment. Please try again.', 'error');
            this.isSubmitting = false;
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }
    }

    resetPaymentForm() {
        const form = $('#addPaymentForm');
        form.trigger('reset');
        form.attr('action', '');
        
        // Reset submission flag
        this.isSubmitting = false;
        
        // Re-enable submit button
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', false);
        submitBtn.html('<i class="fas fa-check me-1"></i>Record Payment');
        
        // Clear display fields
        $('#payment_invoice_number_display').text('-');
        $('#payment_customer_name_display').text('-');
        $('#payment_customer_email_display').text('-');
        $('#payment_customer_phone_display').text('-');
        $('#payment_total_amount_display').text('৳ 0.00');
        $('#payment_due_amount_display').text('৳ 0.00');
        $('#payment_received_amount_display').text('৳ 0.00');
        $('#payment_status_display').text('-');
        $('#payment_status_display').removeClass().addClass('badge bg-secondary');
        
        // Clear product fields
        $('#payment_product_name').text('-');
        $('#payment_product_price').text('৳ 0.00');
        $('#payment_billing_cycle').text('-');
        $('#payment_product_amount').text('৳ 0.00');
        
        // Clear input fields
        $('#received_amount').val('');
        $('#next_due').val('');
        $('#payment_cp_id').val('');
        
        // Reset validation
        $('#payment_amount').removeClass('is-invalid');
        $('#payment_amount_error').hide();
    }

    showToast(message, type = 'info') {
        // Remove existing toasts
        $('.toast').remove();
        
        const toastClass = type === 'error' ? 'alert-danger' : 
                          type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const iconClass = type === 'success' ? 'fa-check' : 
                         type === 'error' ? 'fa-exclamation-triangle' : 
                         type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle';
        
        const toast = $(`
            <div class="alert ${toastClass} alert-dismissible fade show toast" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            // Check if toast still exists in DOM before trying to fade it out
            if (toast && toast.length > 0 && document.body.contains(toast[0])) {
                toast.fadeOut(300, function() {
                    // Use a more robust check for the element's existence
                    if (this && document.body.contains(this)) {
                        $(this).remove();
                    }
                });
            }
        }, 5000);
    }
}

// Initialize payment modal when document is ready
document.addEventListener('DOMContentLoaded', function() {
    new PaymentModal();
});
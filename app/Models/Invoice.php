<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property string $invoice_number
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon $issue_date
 * @property \Illuminate\Support\Carbon $due_date
 * @property string $status
 * @property float $subtotal
 * @property float $tax_amount
 * @property float $discount_amount
 * @property float $total_amount
 * @property float $paid_amount
 * @property float $balance_due
 * @property string $currency
 * @property string|null $notes
 * @property bool $is_recurring
 * @property string|null $recurring_frequency
 * @property \Illuminate\Support\Carbon|null $next_recurring_date
 * @property array|null $billing_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InvoiceItem> $items
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentAllocation> $paymentAllocations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DunningReminder> $dunningReminders
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CreditNote> $creditNotes
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice overdue()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice outstanding()
 * @method static \Database\Factories\InvoiceFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'issue_date',
        'due_date',
        'status',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'balance_due',
        'currency',
        'notes',
        'is_recurring',
        'recurring_frequency',
        'next_recurring_date',
        'billing_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'next_recurring_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'is_recurring' => 'boolean',
        'billing_address' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to only include overdue invoices.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereIn('status', ['issued', 'partially_paid']);
    }

    /**
     * Scope a query to only include outstanding invoices.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOutstanding($query)
    {
        return $query->whereIn('status', ['issued', 'partially_paid', 'overdue']);
    }

    /**
     * Get the customer that owns the invoice.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items for the invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the payment allocations for the invoice.
     */
    public function paymentAllocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    /**
     * Get the dunning reminders for the invoice.
     */
    public function dunningReminders(): HasMany
    {
        return $this->hasMany(DunningReminder::class);
    }

    /**
     * Get the credit notes for the invoice.
     */
    public function creditNotes(): HasMany
    {
        return $this->hasMany(CreditNote::class);
    }

    /**
     * Check if invoice is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date < now() && in_array($this->status, ['issued', 'partially_paid']);
    }
}
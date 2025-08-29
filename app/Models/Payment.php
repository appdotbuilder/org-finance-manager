<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $customer_id
 * @property string $payment_reference
 * @property float $amount
 * @property string $currency
 * @property string $payment_method
 * @property string $status
 * @property string|null $gateway_provider
 * @property string|null $gateway_transaction_id
 * @property array|null $gateway_response
 * @property \Illuminate\Support\Carbon|null $payment_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentAllocation> $allocations
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment completed()
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'payment_reference',
        'amount',
        'currency',
        'payment_method',
        'status',
        'gateway_provider',
        'gateway_transaction_id',
        'gateway_response',
        'payment_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to only include completed payments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the customer that owns the payment.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the payment allocations for the payment.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    /**
     * Get the allocated amount for this payment.
     */
    public function getAllocatedAmountAttribute(): float
    {
        return $this->allocations->sum('allocated_amount');
    }

    /**
     * Get the unallocated amount for this payment.
     */
    public function getUnallocatedAmountAttribute(): float
    {
        return $this->amount - $this->allocated_amount;
    }
}
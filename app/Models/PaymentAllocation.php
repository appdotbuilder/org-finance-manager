<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaymentAllocation
 *
 * @property int $id
 * @property int $payment_id
 * @property int $invoice_id
 * @property float $allocated_amount
 * @property \Illuminate\Support\Carbon $allocated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Payment $payment
 * @property-read \App\Models\Invoice $invoice
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAllocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAllocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAllocation query()
 * @method static \Database\Factories\PaymentAllocationFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class PaymentAllocation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'payment_id',
        'invoice_id',
        'allocated_amount',
        'allocated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'allocated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the payment that owns the allocation.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the invoice that owns the allocation.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
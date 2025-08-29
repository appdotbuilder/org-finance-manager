<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CreditNote
 *
 * @property int $id
 * @property string $credit_note_number
 * @property int $customer_id
 * @property int|null $invoice_id
 * @property \Illuminate\Support\Carbon $issue_date
 * @property float $amount
 * @property string $currency
 * @property string $reason
 * @property string $description
 * @property string $status
 * @property float $applied_amount
 * @property float $refunded_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\Invoice|null $invoice
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|CreditNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CreditNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CreditNote query()
 * @method static \Database\Factories\CreditNoteFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class CreditNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'credit_note_number',
        'customer_id',
        'invoice_id',
        'issue_date',
        'amount',
        'currency',
        'reason',
        'description',
        'status',
        'applied_amount',
        'refunded_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'amount' => 'decimal:2',
        'applied_amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the credit note.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the invoice associated with the credit note.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the remaining credit amount.
     */
    public function getRemainingAmountAttribute(): float
    {
        return $this->amount - $this->applied_amount - $this->refunded_amount;
    }
}
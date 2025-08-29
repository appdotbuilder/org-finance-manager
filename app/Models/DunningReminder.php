<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\DunningReminder
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $type
 * @property int $reminder_level
 * @property \Illuminate\Support\Carbon $sent_at
 * @property string $status
 * @property string|null $message_content
 * @property array|null $delivery_response
 * @property \Illuminate\Support\Carbon|null $next_reminder_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Invoice $invoice
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|DunningReminder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DunningReminder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DunningReminder query()
 * @method static \Database\Factories\DunningReminderFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class DunningReminder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invoice_id',
        'type',
        'reminder_level',
        'sent_at',
        'status',
        'message_content',
        'delivery_response',
        'next_reminder_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reminder_level' => 'integer',
        'sent_at' => 'datetime',
        'delivery_response' => 'array',
        'next_reminder_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the invoice that owns the reminder.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
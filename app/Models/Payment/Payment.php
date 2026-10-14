<?php

namespace App\Models\Payment;

use App\Models\Membership\Membership;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $membership_id
 * @property string $invoice_no
 * @property string $amount
 * @property string $method
 * @property string $status
 * @property Carbon|null $paid_at
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 */
class Payment extends Model
{
    /** @use HasFactory<Factory<Payment>> */
    use HasFactory, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'membership_id',
        'invoice_no',
        'amount',
        'method',
        'status',
        'paid_at',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment): void {
            if (empty($payment->invoice_no)) {
                $payment->invoice_no = 'INV-'.now()->format('Ymd').'-'.random_int(1000, 9999);
            }
        });
    }

    /**
     * @return BelongsTo<Membership, $this>
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }
}

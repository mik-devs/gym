<?php

namespace App\Models\Equipment;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property int $quantity
 * @property Carbon|null $purchase_date
 * @property string $condition
 * @property string $status
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 */
class Equipment extends Model
{
    /** @use HasFactory<Factory<Equipment>> */
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'equipment';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'quantity',
        'purchase_date',
        'condition',
        'status',
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
            'purchase_date' => 'date',
        ];
    }
}

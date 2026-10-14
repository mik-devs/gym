<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $gym_name
 * @property string|null $logo_path
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string $currency
 * @property string $tax_rate
 * @property string $invoice_prefix
 * @property Carbon $opening_time
 * @property Carbon $closing_time
 */
class GymSetting extends Model
{
    /** @use HasFactory<Factory<GymSetting>> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'gym_name',
        'logo_path',
        'address',
        'phone',
        'email',
        'currency',
        'tax_rate',
        'invoice_prefix',
        'opening_time',
        'closing_time',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tax_rate' => 'decimal:2',
            'opening_time' => 'datetime:H:i',
            'closing_time' => 'datetime:H:i',
        ];
    }

    public static function current(): static
    {
        return static::query()->first() ?? static::query()->create([
            'gym_name' => 'Gym',
            'currency' => 'PHP',
            'tax_rate' => '0.00',
            'invoice_prefix' => 'INV-',
            'opening_time' => '06:00:00',
            'closing_time' => '22:00:00',
        ]);
    }
}

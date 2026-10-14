<?php

namespace App\Models\Trainer;

use App\Models\GymClass\GymClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string|null $specialty
 * @property string|null $salary
 * @property Carbon|null $hire_date
 * @property string $status
 */
class Trainer extends Model
{
    /** @use HasFactory<Factory<Trainer>> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'specialty',
        'salary',
        'hire_date',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'salary' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<GymClass, $this>
     */
    public function classes(): HasMany
    {
        return $this->hasMany(GymClass::class);
    }
}

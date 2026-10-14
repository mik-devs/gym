<?php

namespace App\Models\GymClass;

use App\Models\Trainer\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $trainer_id
 * @property int $capacity
 * @property int $duration_minutes
 * @property string|null $location
 * @property string $status
 * @property Carbon|null $deleted_at
 */
class GymClass extends Model
{
    /** @use HasFactory<Factory<GymClass>> */
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'classes';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'trainer_id',
        'capacity',
        'duration_minutes',
        'location',
        'status',
    ];

    /**
     * @return BelongsTo<Trainer, $this>
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * @return HasMany<ClassSchedule, $this>
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }
}

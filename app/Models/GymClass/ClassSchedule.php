<?php

namespace App\Models\GymClass;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $class_id
 * @property string $day_of_week
 * @property Carbon $start_time
 * @property Carbon $end_time
 */
class ClassSchedule extends Model
{
    /** @use HasFactory<Factory<ClassSchedule>> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    /**
     * @return BelongsTo<GymClass, $this>
     */
    public function gymClass(): BelongsTo
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    /**
     * @return HasMany<ClassEnrollment, $this>
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class);
    }
}

<?php

namespace App\Models\GymClass;

use App\Models\Member\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $class_schedule_id
 * @property int $member_id
 * @property string $status
 * @property Carbon $enrolled_at
 */
class ClassEnrollment extends Model
{
    /** @use HasFactory<Factory<ClassEnrollment>> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'class_schedule_id',
        'member_id',
        'status',
        'enrolled_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<ClassSchedule, $this>
     */
    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}

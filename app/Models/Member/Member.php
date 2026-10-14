<?php

namespace App\Models\Member;

use App\Models\Attendance\Attendance;
use App\Models\GymClass\ClassEnrollment;
use App\Models\Membership\Membership;
use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string|null $address
 * @property Carbon $dob
 * @property string $gender
 * @property string|null $photo_path
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property Carbon $join_date
 * @property string $status
 * @property Membership|null $active_membership
 * @property Carbon|null $deleted_at
 */
class Member extends Model
{
    /** @use HasFactory<Factory<Member>> */
    use HasFactory, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'dob',
        'gender',
        'photo_path',
        'emergency_contact_name',
        'emergency_contact_phone',
        'join_date',
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
            'dob' => 'date',
            'join_date' => 'date',
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
     * @return HasMany<Membership, $this>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * @return HasManyThrough<Payment, Membership, $this>
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Membership::class);
    }

    /**
     * @return HasMany<Attendance, $this>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * @return HasMany<ClassEnrollment, $this>
     */
    public function classEnrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    /**
     * @return Attribute<Membership|null, never>
     */
    protected function activeMembership(): Attribute
    {
        return Attribute::make(
            get: fn (): ?Membership => $this->memberships()
                ->where('status', 'active')
                ->latest('start_date')
                ->first(),
        );
    }
}

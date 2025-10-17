<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'amount',
        'method',
        'status',
        'paid_at',
        'attendance_verified',
        'subscription_type_id',
        'subscription_id',
        'payment_type',
        'course_type_id',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'attendance_verified' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function attendance()
{
    return $this->hasOne(Attendance::class, 'user_id', 'user_id')
        ->whereColumn('course_id', 'course_id');
}
public function reservation()
{
    return $this->belongsTo(Reservation::class);
}
public function payment()
{
    return $this->hasOne(Payment::class);
}
public function subscription()
{
    return $this->belongsTo(Subscription::class);
}

public function subscriptionType()
{
    return $this->belongsTo(SubscriptionType::class);
}
  public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

      public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}

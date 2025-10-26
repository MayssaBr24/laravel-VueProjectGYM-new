<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Course extends Model
{
    protected $fillable = [
        'name', 'description', 'coach_name', 'max_participants',
        'start_time', 'end_time', 'coach_id' , 'type', 'price' ,'course_type_id','qr_code'
    ];

    protected $appends = ['current_participants']; 

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getCurrentParticipantsAttribute()
    {
        return $this->reservations()->count(); 
    }

    public function isFull()
    {
        return $this->reservations()->count() >= $this->max_participants;
    }

    public function isReservedByUser($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->reservations()->where('user_id', $userId)->exists();
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    public function courseType()
{
    return $this->belongsTo(CourseType::class);
}
public function attendances()
{
    return $this->hasMany(Attendance::class);
}

public function historiques()
{
    return $this->hasMany(Historique::class);
}
}

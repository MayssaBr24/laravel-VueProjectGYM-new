<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'reservation_date',
        'status',
        'coach_id',
        'room_id',
        'payment_id',
        'is_read',
        

    ];
  public function payment()
    {
        return $this->belongsTo(payment::class);
    }
  

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
     public function coach() {
        return $this->belongsTo(User::class, 'coach_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reservation) {
            
        });
    }
}

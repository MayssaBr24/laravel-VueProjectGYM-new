<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Historique extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'user_role',
        'user_id',
        'course_id',
        'description',
        'action_at',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
   public function reservation()
{
    return $this->belongsTo(Reservation::class);
}
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    


}

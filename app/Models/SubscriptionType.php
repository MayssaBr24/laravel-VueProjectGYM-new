<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // 'Homme', 'Femme', 'Mixte'
        'price',
        'duration_days',
    
    ];
      public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    
}
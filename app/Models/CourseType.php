<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    // Autoriser l'assignation de masse sur ces champs
    protected $fillable = [
        'name',
        'price',
        'subscription_type',
    ];
}

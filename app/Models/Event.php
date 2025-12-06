<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'title',
        'description',
        'venue',
        'date',
        'time_start',
        'time_end',
        'capacity',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
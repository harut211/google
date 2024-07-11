<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;


    protected $fillable=[
        'user_id',
        'event_id',
        'summary',
        'description',
        'start',
        'end'
    ];

    protected $table = 'events';
}

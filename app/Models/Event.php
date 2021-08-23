<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Event extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function account()
    {
        return $this->belongsTo(Account::class, 'foreign_key');
    }
}

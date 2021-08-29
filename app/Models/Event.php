<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'destiny',
        'type',
        'amount'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'foreign_key');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'registered_at'
    ];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }


}

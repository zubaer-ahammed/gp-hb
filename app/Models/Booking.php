<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_number',
        'arrival',
        'checkout',
        'book_type',
        'book_time',
        'customer_id'
    ];

    public function payments() {
        return $this->hasMany(Payment::class);
    }

}

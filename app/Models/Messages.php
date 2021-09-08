<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 
        'customer_receiver_id',
        'staff_id',
        'staff_receiver_id',
        'messages'
    ];

    public function data_customer_id()
    {
        return $this->hasMany('App\Models\Customer', 'id', 'customer_id');
    }

    public function data_customer_receiver_id()
    {
        return $this->hasMany('App\Models\Customer', 'id', 'customer_receiver_id');
    }

    public function data_staff_id()
    {
        return $this->hasMany('App\Models\Staff', 'id', 'staff_id');
    }

    public function data_staff_receiver_id()
    {
        return $this->hasMany('App\Models\Staff', 'id', 'staff_receiver_id');
    }
}

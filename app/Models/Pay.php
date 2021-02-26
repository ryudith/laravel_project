<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pay extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lend_id', 
        'user', 
        'status', 
        'nominal', 
        'pay_file', 
        'note', 
        'data_owner',
    ];


    public function user () 
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }


    public function lend () 
    {
        return $this->belongsTo(Lend::class, 'lend_id', 'id');
    }


    public function owner () 
    {
        return $this->belongsTo(User::class, 'data_owner', 'id');
    }
}

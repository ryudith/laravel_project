<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Lend extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'is_member',
        'user',
        'status',
        'nominal',
        'lend_file',
        'description',
        'data_owner',
    ];


    public function user () 
    {
        if ($this->user > 0) {
            return User::where('id', $this->user)->first();
        }

        return null;
    }
    

    public function owner () 
    {
        return $this->belongTo(User::class, 'data_owner', 'id');
    }

}

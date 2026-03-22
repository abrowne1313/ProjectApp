<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class user_type extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'user_types';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'usertype' ,
    
    ];
}

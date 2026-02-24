<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_type extends Model
{
    use Notifiable;
    protected $table = 'user_type';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'usertype' ,
    
    ];
}

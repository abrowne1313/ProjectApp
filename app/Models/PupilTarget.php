<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PupilTarget extends Model
{
               use Notifiable;
    protected $table = 'pupil_targets';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'Pupil_id' ,
        'Subject_id',
        'Target', 
        'YearGroup'
    
    ];
}

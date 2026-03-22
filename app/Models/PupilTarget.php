<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PupilTarget extends Model
{
               use HasFactory, Notifiable;
    protected $table = 'pupil_targets';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'Pupil_id' ,
        'Subject_id',
        'Target', 
        'YearGroup'
    
    ];

    public function subject()
{
    return $this->belongsTo(Subject::class, 'Subject_id');
}

    public function pupil()
{
    return $this->belongsTo(pupildata::class, 'Pupil_id');
}

}

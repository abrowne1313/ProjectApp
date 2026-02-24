<?php

namespace App\Models;

// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
class PupilData extends Model
{
    use Notifiable;
    protected $table = 'pupil_data';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'FirstName' ,
        'Surname',
        'YearGroup',
        'DateOfBirth',
        'Gender',
        'FormClass',
        'SEN',
        'Medical'   
    
    ];

        public function classes()
    {
        return $this->belongsToMany(
            ClassLists::class,
            'class_pupil',
            'pupil_id',
            'class_id'
        );
    }
}

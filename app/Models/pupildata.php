<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PupilData extends Model
{
    use HasFactory, Notifiable;
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

    public function scores()
{
    return $this->hasMany(PupilScores::class, 'Pupil_id');
}


public function targets()
{
    return $this->hasMany(PupilTarget::class, 'Pupil_id');
}

}

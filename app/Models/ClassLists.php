<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ClassLists extends model
{
            use Notifiable;
    protected $table = 'class_lists';   // From php MyAdmin
    protected $primaryKey = 'id';     

    protected $fillable = [
        'ClassName' ,
        'YearGroup',
        'Subject',
        'teacher_id'
    
    ];
// Pupils can be in more than one class
     public function pupils()
    {
        return $this->belongsToMany(
            PupilData::class,
            'class_pupil',
            'class_id',
            'pupil_id'
        );
    }
        // Each class belongs to one teacher
    public function teacher()
    {
        return $this->belongsTo(UserData::class, 'teacher_id');
    }

    public function subject()
{
    return $this->belongsTo(Subject::class);
}

public function scheme()
{
    return $this->hasOne(Schemes::class, 'Subject_id', 'subjectModel.id')
                ->where('YearGroup', $this->YearGroup);
}



}


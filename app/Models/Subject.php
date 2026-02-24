<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
       use Notifiable;
    protected $table = 'subjects';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'Subject' ,
        'HoD_Teacher_id' 
    
    ];

    public function hodTeacher()
{
    return $this->belongsTo(UserData::class, 'HoD_Teacher_id');
}

public function schemes() {
    return $this->hasMany(Scheme::class);
}

}

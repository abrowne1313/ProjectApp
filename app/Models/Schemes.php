<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Schemes extends Model
{
           use Notifiable;
    protected $table = 'schemes';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'Subject_id' ,
        'YearGroup',
        'CreatedBy' 
    
    ];

    public function subject() {
    return $this->belongsTo(Subject::class, 'Subject_id');
}

public function topics() {
    return $this->hasMany(Topics::class, 'Scheme_id');
}

public function creator() { 
    return $this->belongsTo(UserData::class, 'CreatedBy'); 
    }
}

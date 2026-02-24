<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Topics extends Model
{
           use Notifiable;
    protected $table = 'topics';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'Scheme_id' ,
        'Title',
        'MaxTestScore',
        'TeachingOrder' 
    
    ];

    public function scheme() {
    return $this->belongsTo(Schemes::class, 'Scheme_id');
}

public function subtopics() {
    return $this->hasMany(SubTopics::class, 'Topic_id');
}

}


<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class SubTopics extends Model
{
           use Notifiable;
    protected $table = 'sub_topics';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'Topic_id' ,
        'Title',
        'TeachingOrder' 
    
    ];

    public function topic() {
    return $this->belongsTo(Topics::class, 'Topic_id');
}

}

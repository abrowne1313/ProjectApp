<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class revisionlists extends Model
{
           use HasFactory, Notifiable;
    protected $table = 'revisionlists';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
     'topic_id' ,
    'content'
    
    ];



 public function topic()
{
    return $this->belongsTo(Topics::class);
}

}

<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PupilScores extends Model
{
           use Notifiable;
    protected $table = 'pupil_scores';   // From php MyAdmin
    protected $primaryKey = 'id';     
    
    protected $fillable = [
        'Pupil_id' ,
        'Topic_id',
        'Score' 
    
    ];

    

    public function pupil()
    {
        return $this->belongsTo(pupildata::class, 'Pupil_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topics::class, 'Topic_id');
    }


}

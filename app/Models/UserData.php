<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserData extends Authenticatable
{
    use Notifiable;
    protected $table = 'user_data';   // From php MyAdmin
    protected $primaryKey = 'id';     

    protected $fillable = [
        'FirstName',
        'Surname',
        'UserEmail',
        'password',
        'user_type',
    ];

    // A teacher can have many classes
    public function classes()
    {
        return $this->hasMany(ClassLists::class, 'teacher_id');
    }

    // Tell Laravel which column is the password
    public function getAuthPassword()
    {
        return $this->password;
    }

        public function getEmailForPasswordReset()
    {
        return $this->UserEmail;
    }

    public function userData()
{
    return $this->hasOne(
        UserData::class,
        'id',   // foreign key on user_data table
         );
}

}
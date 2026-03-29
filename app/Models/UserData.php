<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class UserData extends Authenticatable
{
    use HasFactory, Notifiable;
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

    public function type()
{
   
    return $this->belongsTo(user_type::class, 'user_type', 'id');
}


    public function getAuthPassword()
    {
        return $this->password;
    }

// Tell larael to use UserEmail and not email when logging in
public function getAuthIdentifierName()
{
    return 'UserEmail';
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
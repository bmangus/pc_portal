<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Adldap\Laravel\Traits\HasLdapUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasLdapUser, SoftDeletes;

    protected $table = 'users';

    protected $appends = ['isRegistered', 'regId', 'didAttend'];

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email2', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'groups' => 'array',
    ];

    protected $dates = ['deleted_at'];

    public function setRegIdAttribute($value)
    {
        $this->attributes['regId'] = $value;
    }

    public function getRegIdAttribute($value)
    {
        return $this->attributes['regId'] = $value;
    }

    public function setIsRegisteredAttribute($value)
    {
        $this->attributes['isRegistered'] = $value;
    }

    public function getIsRegisteredAttribute($value)
    {
        return $this->attributes['isRegistered'] = $value;
    }

    public function setDidAttendAttribute($value)
    {
        $this->attributes['didAttend'] = $value;
    }

    public function getDidAttendAttribute($value)
    {
        return $this->attributes['didAttend'] = $value;
    }
    //Get Entrollment for PD

    public function registration()
    {
        return $this->hasMany('App\Registration', 'staff_id');
    }

    //Get Enrolled Session Information
    public function showRegistered()
    {
        return $this->belongsToMany('App\PD', 'registrations', 'staff_id', 'pd_id')->withTrashed();
    }

    //Match to email
    public function scopeEmail($query, $email, $email2)
    {
        return $query
            ->where('email', $email)
            ->orWhere('email2', $email);

    }

    public function scopeHomeSchool($query, $school)
    {
        return $query->where('home_school', $school);
    }

    //Get only active users
    public function scopeActive($query)
    {

        //return $query->where('groups', 'like', 'staff')
    }

}

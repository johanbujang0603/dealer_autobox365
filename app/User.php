<?php

namespace App;

use App\Models\PermissionApp;
use App\Models\RolePermission;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'first_name',
        'last_name',
        'gender',
        'photo_src',
        'role',
        'type',
        'locations',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['full_name', 'profile_image_src'];
    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }
    public function getProfileImageSrcAttribute()
    {
        return isset($this->attributes['profile_image']) && $this->attributes['profile_image'] ?
            asset($this->attributes['profile_image']) : "https://ui-avatars.com/api/?bold=true&color=ffffff&background=139ff0&name=" . $this->attributes['first_name'] . '+' . $this->attributes['last_name'];
    }
    public function getCompanyLogoSrcAttribute()
    { }
    public function phone_number_details()
    {
        # code...
        return $this->hasMany('App\Models\UserPhone', 'user_id', 'id');
    }
    public function settings()
    {
        # code...
        return $this->hasOne('App\Models\UserSetting', 'user_id', 'id');
    }
    public function hasPermission($app_slug, $permission) {
        $app_id = PermissionApp::where('app_name_slug', $app_slug)->first()->id;
        $permission_obj = RolePermission::where('app_id', $app_id)->where('role_id', $this->role)->first();
        if ($this->type == 'Dealer')
            return true;
        else {
            if ($permission == 'read') {
                if (isset($permission_obj->read)) {
                    return $permission_obj->read == true ? true : false;
                }
                return false;
            }
            else if ($permission == 'write') {
                if (isset($permission_obj->write)) {
                    return $permission_obj->write == true ? true : false;
                }
                return false;
            }
            else if ($permission == 'delete') {
                if (isset($permission_obj->delete)) {
                    return $permission_obj->delete == true ? true : false;
                }
                return false;
            }
        }
    }
}

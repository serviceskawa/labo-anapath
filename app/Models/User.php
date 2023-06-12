<?php

namespace App\Models;

use App\Models\Role;
use App\Permissions\HasPermissionsTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissionsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'signature',
        'two_factor_enabled',
        'is_connect',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $isconnect = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set attribute $isconnect value
     */
    public function setIsConnect($etat){
        $this->isconnect = $etat;
    }

    public function setLastLoginDeviceAttribute($value)
    {
        $this->attributes['lastlogindevice'] = hash('sha256', $value);
    }

    public function getGoogle2faSecretAttribute()
    {
        return $this->two_factor_secret;
    }

    public function setGoogle2faSecretAttribute($value)
    {
        $this->two_factor_secret = $value;
        $this->save();
    }

    public function generate2faSecret()
    {
        $google2fa = new Google2FA();
        return $google2fa->generateSecretKey();
    }

    // public function getQRCodeGoogleUrlAttribute()
    // {
    //     $google2fa = new Google2FA();
    //     return $google2fa->getQRCodeUrl(
    //         config('app.name'),
    //         $this->email,
    //         $this->two_factor_secret
    //     );
    // }

    public function getGoogle2faUrl()
    {
        return $this->getAttribute('qr_code_google_url');
    }

    public function isGoogle2faEnabled()
    {
        return $this->two_factor_enabled;
    }

    public function enableGoogle2fa()
    {
        $this->two_factor_enabled = true;
        $this->setGoogle2faSecretAttribute($this->generate2faSecret());
    }

    public function disableGoogle2fa()
    {
        $this->two_factor_enabled = false;
        $this->two_factor_secret = null;
        $this->save();
    }

    public function verifyGoogle2fa($oneTimePassword)
    {
        $google2fa = new Google2FA();
        return $google2fa->verifyKey($this->two_factor_secret, $oneTimePassword);
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_roles', 'user_id', 'role_id');
    }

    public function fullname()
    {
        return $this->firstname .' '.$this->lastname ;
    }
}

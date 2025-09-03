<?php

namespace App\Models;

use App\Models\Role;
use App\Permissions\HasPermissionsTrait;
use App\Traits\BranchScopeTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

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
        'commission',
        'whatsapp',
        'telephone',
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

    public function docs()
    {
        return $this->hasMany(Doc::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function expensive()
    {
        return $this->hasMany(Expense::class);
    }

    public function daily()
    {
        return $this->hasMany(CashboxDaily::class);
    }

    /**
     * Set attribute $isconnect value
     */
    public function setIsConnect($etat)
    {
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
        return $this->firstname . ' ' . $this->lastname;
    }


    public function fullUserId()
    {
        return $this->id;
    }

    public function userCheckRole($id)
    {
        $access = UserRole::where('user_id', $this->id)->where('role_id', $id)->first();

        return $access ? true : false;
    }

    public function assignment()
    {
        return $this->hasMany(AssignmentDoctor::class, 'doctor_id');
    }

    // 🔁 Relation vers la branche principale (colonne branch_id dans users)
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
        // Renvoie la branche principale ou actuelle associée à l'utilisateur
    }


    // Relation many-to-many avec la table branch_user
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_user', 'user_id', 'branch_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('branch_filter', function ($builder) {
            if (session()->has('selected_branch_id')) {
                $selectedBranchId = session()->get('selected_branch_id');

                $builder->whereHas('branches', function ($query) use ($selectedBranchId) {
                    $query->where('branch_id', $selectedBranchId);
                });
            }
        });
    }
}

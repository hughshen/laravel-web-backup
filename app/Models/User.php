<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'secret_2fa', 'updated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'qr_code_2fa',
    ];

    /**
     * Get the user's created_at text.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Get the user's updated_at text.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Get the user's login ip.
     *
     * @param  string  $value
     * @return string
     */
    public function getLoginIpAttribute($value)
    {
        return $value > 0 ? long2ip($value) : '0.0.0.0';
    }

    /**
     * Get the user's login time.
     *
     * @param  string  $value
     * @return string
     */
    public function getLoginTimeAttribute($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : 'Never Login';
    }

    /**
     * Get the user's 2fa qr code.
     *
     * @return string
     */
    public function getQRCode2FAAttribute()
    {
        $url = '';
        if (!empty($this->secret_2fa)) {
            $url = app('gauth')->getQRCodeGoogleUrl($this->username, $this->secret_2fa, 'Blog');
        }

        return $url;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends BaseModel implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait, SoftDeletingTrait;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'salt'];

    /**
     * Dates
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password', 'salt', 'email', 'verified', 'deleted_at', 'disabled'];

    /**
     * To check cache
     *
     * Stores a cached user to check against
     *
     * @var object
     */
    protected $to_check_cache;

    /**
     * Groups
     *
     * @return object
     */
    public function groups()
    {
        return $this->belongsToMany(
                Config::get('power::models.group'),
                $this->prefix.'groupUser',
                'userId', 'groupId'
            )
            ->withTimestamps();
    }

    /**
     * Salts and saves the password
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $salt = md5(Str::random(64) . time());
        $hashed = Hash::make($salt . $password);

        $this->attributes['password'] = $hashed;
        $this->attributes['salt'] = $salt;
    }

    /**
     * Verified scope
     *
     * @param  object $query
     * @return object
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', '=', 1);
    }

    /**
     * Unverified scope
     *
     * @param  object $query
     * @return object
     */
    public function scopeUnverified($query)
    {
        return $query->where('verified', '=', 0);
    }

    /**
     * Disabled scope
     *
     * @param  object $query
     * @return object
     */
    public function scopeDisabled($query)
    {
        return $query->where('disabled', '=', 1);
    }

    /**
     * Enabled scope
     *
     * @param  object $query
     * @return object
     */
    public function scopeEnabled($query)
    {
        return $query->where('disabled', '=', 0);
    }
}

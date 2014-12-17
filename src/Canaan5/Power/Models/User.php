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
     * Is the User a Group
     *
     * @param  array|string  $group A single group or an array of groups
     * @return boolean
     */
    public function is($groups)
    {
        $groups = !is_array($groups)
            ? [$groups]
            : $groups;

        $to_check = $this->getToCheck();

        $valid = FALSE;
        foreach ($to_check->groups as $group)
        {
            if (in_array($group->name, $groups))
            {
                $valid = TRUE;
                break;
            }
        }

        return $valid;
    }

    /**
     * Can the User do something
     *
     * @param  array|string $permissions Single permission or an array or permissions
     * @return boolean
     */
    public function can($permissions)
    {
        $permissions = !is_array($permissions)
            ? [$permissions]
            : $permissions;

        $to_check = $this->getToCheck();

        // Are we a super admin?
        foreach ($to_check->groups as $group)
        {
            if ($group->name === Config::get('power::super_admin'))
            {
                return TRUE;
            }
        }

        $valid = FALSE;
        foreach ($to_check->groups as $group)
        {
            foreach ($group->permissions as $permission)
            {
                if (in_array($permission->name, $permissions))
                {
                    $valid = TRUE;
                    break 2;
                }
            }
        }

        return $valid;
    }

    /**
     * Is the User a certain Level
     *
     * @param  integer $level
     * @param  string $modifier [description]
     * @return boolean
     */
    public function level($level, $modifier = '>=')
    {
        $to_check = $this->getToCheck();

        $max = -1;
        $min = 100;
        $levels = [];

        foreach ($to_check->groups as $group)
        {
            $max = $group->level > $max
                ? $group->level
                : $max;

            $min = $group->level < $min
                ? $group->level
                : $min;

            $levels[] = $group->level;
        }

        switch ($modifier)
        {
            case '=':
                return in_array($level, $levels);
                break;

            case '>=':
                return $max >= $level;
                break;

            case '>':
                return $max > $level;
                break;

            case '<=':
                return $min <= $level;
                break;

            case '<':
                return $min < $level;
                break;

            case '!=':
                return !in_array($level, $levels);
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * Get to check
     *
     * @return object
     */
    private function getToCheck()
    {

        if(empty($this->to_check_cache))
        {
        	$key = static::getKeyName();

            $to_check = static::with(['groups', 'groups.permissions'])
                ->where($key, '=', $this->attributes[$key])
                ->first();

            $this->to_check_cache = $to_check;
        }
        else
        {
            $to_check = $this->to_check_cache;
        }

        return $to_check;
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

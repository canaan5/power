<?php

class Group extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'level'];

    /**
     * To check cache
     *
     * Stores a cached user to check against
     *
     * @var object
     */
    protected $to_check_cache;

    /**
     * Users
     *
     * @return object
     */
    public function users()
    {
        return $this->belongsToMany(
                Config::get('power::models.user'),
                $this->prefix.'groupUser',
                'userId', 'groupId'
            )
        ->withTimestamps();
    }

    /**
     * Permissions
     *
     * @return object
     */
    public function permissions()
    {
        return $this->belongsToMany(
                Config::get('power::models.permission'),
                $this->prefix.'permissionGroup',
                'permissionId', 'groupId'
            )
        ->withTimestamps();
    }
}

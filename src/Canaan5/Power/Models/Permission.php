<?php

class Permission extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * Roles
     *
     * @return object
     */
    public function groups()
    {
        return $this->belongsToMany(
                Config::get('power::models.group'),
                $this->prefix.'permissionGroup',
                'groupId', 'permissionId'
            )
        ->withTimestamps();
    }
}

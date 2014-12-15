<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Power Role Model
	|--------------------------------------------------------------------------
	|
	| This is the Role model used by Power to create correct relations.  Update
	| the role if it is in a different namespace.
	|
	*/
	'role' => '\Role',

	/*
	|--------------------------------------------------------------------------
	| Power Roles Table
	|--------------------------------------------------------------------------
	|
	| This is the Roles table used by Power to save roles to the database.
	|
	*/
	'roles_table' => 'roles',

	/*
	|--------------------------------------------------------------------------
	| Power Group Model
	|--------------------------------------------------------------------------
	|
	| This is the Group model used by Power.
	|
	*/
	'role' => 'Group',

	/*
	|--------------------------------------------------------------------------
	| Power Group Model
	|--------------------------------------------------------------------------
	|
	| This is the Group table used by Power to save groups to the database.
	|
	*/
	'group_table'	=> 'groups',

	/*
	|--------------------------------------------------------------------------
	| Power Permission Model
	|--------------------------------------------------------------------------
	|
	| This is the Permission model used by Power to create correct relations.  Update
	| the permission if it is in a different namespace.
	|
	*/
	'permission' => '\Permission',

	/*
	|--------------------------------------------------------------------------
	| Power Permissions Table
	|--------------------------------------------------------------------------
	|
	| This is the Permissions table used by Power to save permissions to the database.
	|
	*/
	'permissions_table' => 'permissions',

	/*
	|--------------------------------------------------------------------------
	| Power permission_role Table
	|--------------------------------------------------------------------------
	|
	| This is the permission_role table used by Power to save relationship between permissions and roles to the database.
	|
	*/
	'permission_role_table' => 'permission_role',

	/*
	|--------------------------------------------------------------------------
	| Power assigned_roles Table
	|--------------------------------------------------------------------------
	|
	| This is the assigned_roles table used by Power to save assigned roles to the database.
	|
	*/
	'assigned_roles_table' => 'assigned_roles',

]
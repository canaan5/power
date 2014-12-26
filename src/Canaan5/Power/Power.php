<?php namespace Canaan5\Power;

/**
 * @license MIT
 * @package Canaan5/Power
 */
class Power
{
	public function __construct(\Illuminate\Foundation\Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Get the current Logged in user or nothing if no user is Authenticated
	 * @return array current Logged in user
	 */
	public function user()
	{
		return $this->app->auth->user();
	}

	/**
	 * Delete a user
	 * @param  int $id user id
	 * @return bool    return true if user deletion is successfull.
	 */
	public function delete($id)
	{
		$user = $this->model($id);
		return $user->delete();
	}

	/**
	 * Verify a user
	 * @param  [type] $id user id
	 * @return true     return true of verifying.
	 */
	public function verify($id)
	{
		$user = $this->model($id);

		if ( ! is_null($user) )
			return $user->update(['verified' => 1]);

		throw new Canaan5\Power\Exceptions\ErrorVerifyingUserException("There is an error verifying this user");
	}

	/**
	 * Login user into the application
	 * @var array credentials
	 */
	public function login($remember = false)
	{
		$credentials =
		[
			'id' => \Input::get('email') ? \Input::get('email') : \Input::get('username'),
			'password' => \Input::get('password')
		];

		try {

			if ( $remember == false )
			{
				\Auth::attempt($credentials);
			} else {

				\Auth::attempt($credentials, true);
			}

		} catch (\Exception $e) {

			return \Redirect::back()->with('authError', $e->getMessage());
		}
	}

	/**
	 * Check if the current logged in or a giving user is super_admin
	 */
	public function oga4Top($userId = null)
	{
		if ( ! is_null($userId))
		{
			if ( $this->group($userId) == 'Oga4Top')
			{
				return true;
			}

			return false;
		}

		if ( $this->group($this->user()->id) == 'Oga4Top')
		{
			return true;
		}

		return false;
	}

	public function group($userId)
	{
		$user = \User::find($userId);

		if ( $user )
		{
			foreach ( $user->groups as $g)
			{
				return $g->name;
			}

		} else {

			return 'User not found';
		}

		return false;

	}

	/**
	 * get the User Model
	 * @param $id
	 * @return mixed
	 */
	public function model($id)
	{
		$model = ucfirst(\Config::get('auth.model', \Config::get('power::models.user')));
		$user = $model::find($id);

		return $user;
	}
}
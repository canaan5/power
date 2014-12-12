<?php
namespace Canaan5\Power;

class Power Implements PowerAuthInterface
{
	public function checkAuth()
	{
		return \Auth::check();
	}

	public function authMe(Array $credentials, $remember = false)
	{
		return \Auth::attempt($credentials, $remember);
	}
}
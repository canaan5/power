<?php namespace Canaan5\Power;

Interface PowerAuthInterface
{
	public function checkAuth();

	public function authMe(Array $credentials, $remember = false);
}
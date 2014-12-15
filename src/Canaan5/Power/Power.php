<?php
namespace Canaan5\Power;

class Power
{
	public function __construct(\Illuminate\Foundation\Application $app)
	{
		$this->app = $app;
	}

	public function user()
	{
		return $this->app->auth->user();
	}
}
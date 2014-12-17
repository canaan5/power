<?php
namespace Canaan5\Power\Facades;

use Illuminate\Support\Facades\Facade;

class Power extends Facade
{
	Protected static function getFacadeAccessor() {

		return 'power';
	}
}
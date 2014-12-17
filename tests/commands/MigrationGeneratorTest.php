<?php

use Canaan5\Power\PowerGenerator;
use Symfony\Component\Console\Tester\CommandTester;

class MigrationGenerationTest extends PHPUnit_Framework_TestCase
{
	public function testOutput()
	{
		$tester = new CommandTester( new PowerGenerator );

		$tester->execute(['name' => 'foo']);

		$this->assertEquals("The argument is foo", $tester->getDisplay());
	}
}
<?php namespace Canaan5\Power\Commands;

use Canaan5\Power\PowerGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModelsGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'power:models';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate all Models for this package.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

		$this->line('');
		$this->line('..................................................................');
		$this->line('');
        if ( $this->confirm("Do you want proceed with Power Models creation? [Yes|no]") ) {

        $this->line('');
		$this->line('..................................................................');
		$this->line('');

            $this->info( "Creating Power Models..." );
            if ( $this->CreatePowerModels() ) {

                $this->info( "Models successfully created!" );

            } else {
                $this->error(
                    "Coudn't create Models.\n Check the write permissions".
                    " within the app/models directory."
                );
            }
        }
	}

	/**
	 * Create models in main app models directory.
	 *
	 * @return array
	 */

	public function CreatePowerModels()
	{
		$dest = $this->laravel->path.'/models';

		$dir = __DIR__ . '/../Models';

		$f = new Filesystem;

		if ( $f->copyDirectory($dir, $dest) )
		{
			return true;
		}

		return false;
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('table', null, InputOption::VALUE_OPTIONAL, 'Groups table.', 'groups'),
		);
	}

}

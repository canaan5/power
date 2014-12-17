<?php namespace Canaan5\Power\Commands;

use Canaan5\Power\PowerGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrationGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'power:migration';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a migration for the power package.';

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
        if ( $this->confirm("Do you want proceed with Power migrations creation? [Yes|no]") ) {

        $this->line('');
		$this->line('..................................................................');
		$this->line('');

            $this->info( "Creating Power migration..." );
            if ( $this->CreatePowerMigration() ) {

                $this->info( "Migration successfully created!" );

            } else {
                $this->error(
                    "Coudn't create migration.\n Check the write permissions".
                    " within the app/database/migrations directory."
                );
            }
        }

	}

	public function CreatePowerMigration()
	{
		$file1 = $this->laravel->path . "/database/migrations/" . date('Y_m_d_His') ."_power_migration_table.php";
		$file2 = $this->laravel->path . "/database/migrations/" . date('Y_m_d_His') ."_power_soft_delete_table.php";

		$output1 = $this->laravel->view->make('power::PowerGeneratorView')->with('table', 'roles')->render();
		$output2 = $this->laravel->view->make('power::PowerGeneratorSoftDelete')->with('table', 'roles')->render();

		if (!file_exists($file1) && $fs = fopen($file1, 'x')) {
            fwrite($fs, $output1);
            fclose($fs);

             if (!file_exists($file2) && $fs = fopen($file2, 'x')) {
	            fwrite($fs, $output2);
	            fclose($fs);
	            return true;
	        }
        }



        return false;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	// protected function getArguments()
	// {
	// 	return array(
	// 		array('name', InputArgument::REQUIRED, 'An example argument.'),
	// 	);
	// }

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('table', null, InputOption::VALUE_OPTIONAL, 'Roles table.', 'roles'),
		);
	}

}

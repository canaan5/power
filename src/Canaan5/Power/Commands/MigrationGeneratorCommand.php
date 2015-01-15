<?php namespace Canaan5\Power\Commands;

use Canaan5\Power\PowerGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;

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
		$des1 = $this->laravel->path . "/database/migrations/" . date('Y_m_d_His') ."_power_migration_table.php";
		$des2 = $this->laravel->path . "/database/migrations/" . date('Y_m_d_His') ."_power_soft_delete_table.php";
		$des3 = $this->laravel->path . "/database/seeds/PowerTableSeeder.php";

		$file1 = __DIR__ . '/../../../migrations/PowerMigrationTable.php';
		$file2 = __DIR__ . '/../../../migrations/PowerSoftDeleteTable.php';
		$file3 = __DIR__ . '/../../../seeds/PowerTableSeeder.php';

		$f = new Filesystem;

		if ( $f->copy($file1, $des1) && $f->copy($file2, $des2 ) && $f->copy($file3, $des3))
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
			array('table', null, InputOption::VALUE_OPTIONAL, 'Roles table.', 'roles'),
		);
	}

}

<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Model\Tasks;

class TakeSnap extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'takesnap';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Take snap of meeting and tasks on every 24 Hrs';

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
		/*just added thsi line in cron job

		* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1
		
		*/
		echo "===================";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['example', InputArgument::OPTIONAL, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}

<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Model\Tasks;

class TaskMonitor extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'taskmonitor';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check the task due date and its active time length. Based on this, change the task status.';

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
		//'waiting','rejected','open','close','expired','timeout','failed'
		$tasksTimeout = Tasks::where("status","=","waiting")->whereRaW("DATEDIFF(now(), updated_at) > 3")
				->update(['status'=>'timeout']);
		// ->get();

		$tasksExpired = Tasks::where("status","=","timeout")->whereRaW("DATEDIFF(now(), updated_at) > 3")
				->update(['status'=>'expired']);

		$tasksFailed = Tasks::where("status","=","open")->whereRaW("DATEDIFF(now(), due) > 0")
				->update(['status'=>'failed']);

				//->update(['title'=>'cron bulk updated']);
		// foreach ($tasksTimeout as $key => $value)
		// {
		// 	$this->info($value->title);
		// 	$this->info($value->updated_at);
		// }
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

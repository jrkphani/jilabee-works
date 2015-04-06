<?php namespace App\Http\Controllers;
use App\Model\Minutes;
use App\Model\Minuteshistory;
use App\Model\Notes;
use App\User;
use Auth;
use Request;
class MinutesController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('minutes.home');
	}
	
	public function getAdd($id=NULL)
	{
		//print_r(Minutes::all());
		$users = User::where('id','!=',Auth::user()->id)->lists('name','id');
		if($id)
		{
			$minute = Minutes::find($id);
			return view('minutes.edit',['minute'=>$minute,'users'=>$users]);
		}
		else
		{
			return view('minutes.add',array('users'=>$users));
		}
	}
	public function postAdd($id=NULL)
	{
		$input = Request::only('title', 'label','venue','attendees','minuters');
		$validation = Minutes::validation($input);

		if ($validation->fails())
		{
			if($id)
			{
				return redirect('minute/edit/'.$id)->withErrors($validation);
			}
			else
			{
				return redirect('minute/add/')->withInput($input)->with('minuters',$input['minuters'])
				->with('attendees',$input['attendees'])->withErrors($validation);
			}
		}
		$input['updated_by'] = Auth::user()->id;
		$input['attendees'] = implode(',', $input['attendees']);
		$input['minuters'] = implode(',', $input['minuters']);
		if($id)
		{
			$minute = Minutes::find($id);
			if($minute->update($input))
			{
				return redirect('minute/edit/'.$id)->with('message', 'Minute added successfully');
			}
			else
			{
				return redirect('minute/edit/'.$id)->with('error', 'Oops something went wrong!');			
			}
		}
		else
		{
			$input['created_by'] = Auth::user()->id;
			if(Minutes::create($input))
			{
				return redirect('minute/add/')->with('message', 'Minute added successfully');
			}
			else
			{
				return redirect('minute/add/')->with('error', 'Oops something went wrong!')
				->withInput($input);
			}	
		}
		
		
	}
	public function list_minutes()
	{
		$uid = Auth::id();
		if(Auth::user()->profile->role == '999')
		{
			$minutes = Minutes::all();
		}
		else
		{
			$minutes = Minutes::whereRaw('FIND_IN_SET('.$uid.',attendees)')->orWhereRaw('FIND_IN_SET('.$uid.',minuters)')->get();	
		}
		
		//$minutes = Minutes::orderBy('updated_at', 'desc')->get();
		//print_r($minutes); die;
		return view('minutes.list',array('minutes'=>$minutes));
	}
	public function list_history($id)
	{
		$minutes = Minutes::find($id);
		// echo "<pre>";
		//echo "i am here";
		// print_r($notes);
		//die;
		if($minutes->minute_history()->where('lock_flag','=',Auth::user()->id)->count())
		{
			//redirect to add notes page if same user
			$mhid = $minutes->minute_history()->where('lock_flag','=',Auth::user()->id)->first()->id;
			return redirect('notes/add/'.$mhid);
		}
		else
		{
			return view('minutes.history',array('minutes'=>$minutes));
		}
	}
}

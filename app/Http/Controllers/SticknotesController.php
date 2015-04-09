<?php namespace App\Http\Controllers;
use Auth;
use Request;
use App\Model\Stickynotes;
class SticknotesController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index($uid=NULL)
	{
		$result = Stickynotes::where('created_by',Auth::id())->orderBy('updated_at','DESC')->get();
		return view('notes.stickynotes',['stickynotes'=>$result]);
	}
	public function postData($id=NULL)
	{	 
		$input = Request::only('description');
		$input['description'] = nl2br($input['description']);
		$input['updated_by'] = Auth::id();
		if($id)
		{
			//update
		}
		else
		{
			//add new
			$input['created_by'] = Auth::id();
			if(Stickynotes::create($input))
			{
				return redirect('stickynotes');	
			}
			else
			{
				return redirect('stickynotes')->withInput();	
			}
			
		}
		
	}
	public function remove($id)
	{
		Stickynotes::where('id', '=', $id)->where('created_by','=',Auth::id())->delete();
		return redirect('stickynotes');
	}

}

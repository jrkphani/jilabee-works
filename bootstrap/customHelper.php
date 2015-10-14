<?php

/**
 * Configures a tenant's database connection.

 * @param  string $tenantName The database name.
 * @return void
 */
// function configureConnection($connectionName)
// {
//     // Just get access to the config. 
//     $config = App::make('config');

//     // Will contain the array of connections that appear in our database config file.
//     $connections = $config->get('database.connections');

//     // This line pulls out the default connection by key (by default it's `mysql`)
//     $defaultConnection = $connections[$config->get('database.default')];

//     // Now we simply copy the default connection information to our new connection.
//     $newConnection = $defaultConnection;
    
//     // Override the database name.
//     $newConnection['database'] = $connectionName;
    
//     // This will add our new connection to the run-time configuration for the duration of the request.
//     App::make('config')->set('database.connections.'.$connectionName, $newConnection);
// }
function generatePublicUserId($id)
{
    return "GEN".dechex($id).date('s');
}
function generateCustomerId($id)
{
    return 'ORG'.dechex($id).date('s');
}
function generateUserId($customerId,$id)
{
    return $customerId.'u'.dechex($id);
}
function getOrgId()
{
    if(starts_with(Auth::user()->userId, 'GEN'))
    {
        return false;
    }
    else
    {
        return substr(Auth::user()->userId, 0, strrpos( Auth::user()->userId, 'u'));
    }
}
function getProfile($where=NULL)
{
    if(Auth::check())
    {
        if(!$where)
        {
            $where['userId'] = Auth::id();
        }
        return App\Model\Profile::where($where)->first();
    }
    else
    {
        return false;
    }
}
function getUser($where=NULL)
{
    if(Auth::check())
    {
        if(!$where)
        {
             return Auth::user();
        }
        return App\User::where($where)->first();
    }
    else
    {
        return false;
    }
}
function isEmail($str)
{
    if(filter_var($str, FILTER_VALIDATE_EMAIL))
    {
        return $str;
    }
    return false;
}
function getDomainFromEmail($email)
	{
	    // Get the data after the @ sign
	    $domain = substr(strrchr($email, "@"), 1);
	 
	    return $domain;
	}
function roles()
    {
        return App\Model\Roles::all()->lists('name','id');
    }
function sendEmail($toEmail,$toName,$subject,$view,$arrayToView,$attachment=NULL)
    {
        $mailArr['fromEmail'] = 'noreply@anabond.com';
        $mailArr['fromName'] = 'Jotter';
        $mailArr['toEmail'] = $toEmail;
        $mailArr['toName'] = $toName;
        $mailArr['subject'] = $subject;
        $mailArr['attachment'] = $attachment;
        Mail::send(
          $view,$arrayToView,
          function( $message ) use ($mailArr){
            $message->from($mailArr['fromEmail'],$mailArr['fromName']);
            $message->to($mailArr['toEmail'],$mailArr['toName']);
            $message->subject($mailArr['subject']);
            if($mailArr['attachment'])
            {
                $message->attach($mailArr['attachment']);
            }
          }
        );
    }
function setNotification($data)
{
    if(!isset($data['parentId']))
    {
        $data['parentId'] = NULL;
    }
    if(!isset($data['isRead']))
    {
        $data['isRead'] = '0';
    }
    App\Model\Notifications::create($data);
}
function readNotification($data)
{
    $check = App\Model\Notifications::where($data)->where('isRead','0')->update(['isRead'=>1]);
}
// function removeNotification($data)
// {
// //$data should contain objectId,objectType and parentId if
//     if(!isset($data['parentId']))
//     {
//         $data['parentId'] = NULL;
//     }
//     $check = App\Model\Notifications::where($data)->delete();
// }
function getAdmin()
{
    if(starts_with(Auth::user()->userId, 'GEN'))
    {
        return false;
    }
    else
    {
        $org =  substr(Auth::user()->userId, 0, strrpos( Auth::user()->userId, 'u'));
        return App\User::where('users.userId','LIKE',$org.'%')->where('isAdmin','1')->first();
    }
}
?>
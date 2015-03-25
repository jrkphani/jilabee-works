<?php
use Illuminate\Database\Seeder;
use App\User;
use App\Model\Profile;

class UserTableSeeder extends Seeder {

  public function run()
  {
    $user = User::create(array(
      'email' => 'admin@jotter.com',
      'password' => bcrypt('password'),
      'name' => 'Jotter-Admin',
    ));
    if($user)
    {
      $data  = array('dob'=>date('Y-m-d'),
              'gender'=>'M',
              'phone'=>'0000000000',
              'role'=>'999',
              'created_by'=>$user->id,
              'updated_by'=>$user->id);
      $profile = new Profile($data);
      $user->profile()->save($profile);
    }
  }

}
?>
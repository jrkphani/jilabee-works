<?php
use Illuminate\Database\Seeder;
use App\Model\Roles;

class RolesTableSeeder extends Seeder {

  public function run()
  {
    //Roles::truncate();
    $roles = array(['name'=>'Attendee'],['name'=>'Minture']);
    Roles::insert($roles);
  }

}
?>
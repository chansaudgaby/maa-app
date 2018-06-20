<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $users[] = [
            "lname" => "Tarihaa",
            "fname" => "Teraitea",
            "password" => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
            // "c_password" => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
            "email" => "teraitea.tarihaa@hotmail.fr",
            "userstype_id" => 1
        ];
        
        foreach($users as $user):

            User::create($user);
            
            // DB::table('userTypes')->insert($userType);
        endforeach;
    }
}

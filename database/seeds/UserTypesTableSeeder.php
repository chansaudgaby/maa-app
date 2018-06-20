<?php

use Illuminate\Database\Seeder;
use App\UserType;

class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = ['traiteur','collaborateur','assistant'];
        $userTypes = [];

        for($i = 0; $i < 3; $i++) :


            $userTypes[]  = [
                'name' => $type[$i],

            ];
        endfor;
        
        foreach($userTypes as $userType):

            UserType::create($userType);
            
            // DB::table('userTypes')->insert($userType);
        endforeach;
    }
    
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $avatarList = ['female1.jpeg', 'female2.png', 'male1.png', 'male2.jpeg'];
        $users = array();
        for($i = 1; $i <= 10; $i++){
            $avatarID = rand(0, 3);
            $users[] = array(
                'id' => $i,
                'alias' => 'USR' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'avatar' => $avatarList[$avatarID]
            );
        }
        DB::table('users')->insert($users);
    }
}

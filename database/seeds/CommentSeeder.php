<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $firstLevelComment = array();
        for($i = 1; $i <= 15; $i++){
            $userID = rand(1, 10);
            $createdAt = Carbon\Carbon::now()->subMinutes($i);
            $firstLevelComment[] = array(
                'id' => $i,
                'user_id' => $userID,
                'comment' => 'A test comment made by USR' . str_pad($userID, 7, '0', STR_PAD_LEFT) . ' at ' . $createdAt->toDateTimeString() .'.',
                'created_at' => $createdAt
            );
        }
        DB::table('comments')->insert($firstLevelComment);

        $secondLevelComment = array();
        for($i = 16; $i <= 30; $i++){
            $userID = rand(1, 10);
            $parentID = rand(1,15);
            $createdAt = Carbon\Carbon::now()->subMinutes($i);
            $secondLevelComment[] = array(
                'id' => $i,
                'user_id' => $userID,
                'comment' => 'A test reply comment made by USR' . str_pad($userID, 7, '0', STR_PAD_LEFT) . ' at ' . $createdAt->toDateTimeString() .'.',
                'parentID' => $parentID,
                'created_at' => $createdAt
            );
        }
        DB::table('comments')->insert($secondLevelComment);

        $thirdLevelComment = array();
        for($i = 31; $i <= 45; $i++){
            $userID = rand(1, 10);
            $parentID = rand(16,30);
            $createdAt = Carbon\Carbon::now()->subMinutes($i);
            $thirdLevelComment[] = array(
                'id' => $i,
                'user_id' => $userID,
                'comment' => 'A test reply comment made by USR' . str_pad($userID, 7, '0', STR_PAD_LEFT) . ' at ' . $createdAt->toDateTimeString() .'.',
                'parentID' => $parentID,
                'created_at' => $createdAt
            );
        }
        DB::table('comments')->insert($thirdLevelComment);
    }
}

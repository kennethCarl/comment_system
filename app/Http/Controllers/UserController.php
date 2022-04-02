<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function createUser(Request $request){
        $response = array('status' => 0, 'message' => '', 'errors' => null);
        try {
            $data = $request->all();
            DB::beginTransaction();
            if($data['alias'] === ""){
                $response['errors']['alias'] = "is required!";
            }else if($data['avatar'] === ""){
                $response['errors']['avatar'] = "is required!";
            }else{
                $user = new User();
                $user = $user->where('alias', '=', $data['alias'])->first();

                if($user === null) {
                    $attributes = array(
                        'alias' => $data['alias'],
                        'avatar' => $data['avatar']);
                    $record = User::create($attributes);
                    $response['record'] = $record;
                }else{
                    $user->update(['avatar' => $data['avatar']]);
                    $response['record'] = $user->where('alias', '=', $data['alias'])->get()[0];
                }


                DB::commit();
                $response['status'] = 1;
            }
        }catch (\Exception $exception){
            DB::rollBack();
            $response['message'] = "An error has occurred. Please contact administrator via email: familyinspiredprojects@gmail.com";
        }
        return response()->json($response);
    }
}

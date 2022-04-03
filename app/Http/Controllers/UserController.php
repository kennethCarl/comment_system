<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function createUser(Request $request){
        $response = array('status' => 1, 'message' => '', 'errors' => null);
        try {
            $data = $request->all();
            $request->validate(array(
                'alias' => ['required', 'string', 'max:10'],
                'avatar' => ['required', 'string', 'max:50'],
            ),
                [
                    'alias.max' => 'Alias must have a max content of 10.',
                    'avatar.max' => 'Alias must have a max content of 50.',
                    'required' => 'The :attribute is required!'
                ]
            );

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
        }catch(ValidationException $exception){
            $errors = $exception->validator->errors()->toArray();
            foreach ($errors as $key => $value){
                $response['errors'][$key] = implode(" ", $value);;
            }
            $response['status'] = 0;
        }catch (\Exception $exception){
            $response['status'] = 0;
            $response['message'] = "An error has occurred. Please contact administrator via email: familyinspiredprojects@gmail.com";
        }
        return response()->json($response);
    }
}

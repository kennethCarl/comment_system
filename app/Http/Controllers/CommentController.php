<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function retrieveTotalComments(){
        $response = array('status' => 1, 'message' => null, 'records' => array());
        try {
            $comments = new Comment();
            $response['count'] = $comments->count();
        }catch (\Exception $exception){
            $response['status'] = 0;
            $response['message'] = "An error has occurred. Please contact administrator via email: familyinspiredprojects@gmail.com";
        }
        return response()->json($response);
    }

    public function retrieveComments(Request $request){
        $response = array('status' => 1, 'message' => null, 'records' => array());
        try {
            $data = $request->all();
            $records = new Comment();

            $records = $records
                ->with('user')
                ->withCount('replies')
                ->where('parentID', '=', 0)
                ->skip((int)$data['currentLength'])->take(100)
                ->orderByDesc('created_at')
                ->get();
            $response['records'] = $records;
        }catch (\Exception $exception){
            $response['status'] = 0;
            $response['message'] = "An error has occurred. Please contact administrator via email: familyinspiredprojects@gmail.com";
        }
        return response()->json($response);
    }

    public function retrieveChildComments(Request $request){
        $response = array('status' => 1, 'message' => null, 'records' => array());
        try {
            $data = $request->all();
            $records = new Comment();

            $records = $records
                ->with('user')
                ->withCount('replies')
                ->where('parentID', '=', $data['parentID'])
                ->skip((int)$data['currentLength'])->take(100)
                ->orderByDesc('created_at')
                ->get();
            $response['records'] = $records;
        }catch (\Exception $exception){
            $response['status'] = 0;
            $response['message'] = "An error has occurred. Please contact administrator via email: familyinspiredprojects@gmail.com";
        }
        return response()->json($response);
    }
}

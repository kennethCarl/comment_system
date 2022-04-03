<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    private function setResponse(){
        return array('status' => 1, 'message' => null, 'records' => array());;
    }
    public function retrieveTotalComments(){
        $response = $this->setResponse();
        try {
            $comments = new Comment();
            $response['count'] = $comments->where('status', '=', 1)->count();
        }catch (\Exception $exception){
            $response['status'] = 0;
            $response['message'] = "An error has occurred. Please contact administrator via email: familyinspiredprojects@gmail.com";
        }
        return response()->json($response);
    }

    public function retrieveComments(Request $request){
        $response = $this->setResponse();
        try {
            $data = $request->all();
            $records = new Comment();

            $records = $records
                ->with('user')
                ->withCount('replies')
                ->where('parentID', '=', 0)
                ->where('status', '=', 1)
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
        $response = $this->setResponse();
        try {
            $data = $request->all();
            $records = new Comment();

            $records = $records
                ->with('user')
                ->withCount('replies')
                ->where('parentID', '=', $data['parentID'])
                ->where('status', '=', 1)
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

    public function createComment(Request $request){
        $response = $this->setResponse();
        try {
            $data = $request->all();
            $request->validate(array(
                'comment' => ['required', 'string', 'max:200'],
                'user_id' => ['required', 'integer'],
                ),
                [
                    'comment.max'=> 'Comment must have a max content of 200.', // custom message
                    'required'=> 'The :attribute is required.' // custom message
                ]
            );

            $record = Comment::create(array(
                'comment' => $data['comment'],
                'user_id'=> $data['user_id'],
                'parentID' => $data['parentID']));

            $response['record'] = $record;
        }catch(ValidationException $exception){
            $errorMessage = '';
            $errors = $exception->validator->errors()->toArray();
            foreach ($errors as $error){
                $errorMessage = $errorMessage . implode(" ", $error);
            }
            $response['status'] = 0;
            $response['message'] = $errorMessage;
        }catch (\Exception $exception){
            $response['status'] = 0;
            $response['message'] = "An error has occurred. Please contact administrator via email: familyinspiredprojects@gmail.com";
        }
        return response()->json($response);
    }

    private function deleteComment($commentID){
        Comment::where('id', $commentID)->update(['status' => 0]);

        // Check if has child
        $countChildComment = new Comment();
        $countChildComment = $countChildComment->where('parentID', '=', $commentID)->count();
        if($countChildComment > 0){
            $childComments = new Comment();
            $childComments = $childComments->where('parentID', '=', $commentID)->get();
            foreach ($childComments as $childComment){
                $this->deleteComment($childComment->id);
            }
        }
    }

    public function deleteComments(Request $request){
        $response = $this->setResponse();
        try {
            $data = $request->all();
            $this->deleteComment($data['commentID']);
            $comments = new Comment();
            $response['count'] = $comments->where('status', '=', 1)->count();
        }catch (\Exception $exception){
            $response['status'] = 0;
            $response['message'] = "An error has occurred. Please contact administrator via email: familyinspiredprojects@gmail.com";
        }
        return response()->json($response);
    }
}

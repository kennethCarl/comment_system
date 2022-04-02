<?php


namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $guarded = ["id"];
    protected $appends = ['comment_date_time', 'comment_timestamp'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parentID');
    }

    public function getCommentDateTimeAttribute(){
        return $this['created_at']->diffForHumans();
    }
    public function getCommentTimestampAttribute(){
        return strtotime($this['created_at']);
    }
}

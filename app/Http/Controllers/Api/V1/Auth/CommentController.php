<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Events\CommentEvent;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //

    public function fetchComments()
    {
        $comments = Comment::all();

        return response()->json($comments);
    }

    public function store(Request $request)
    {

        if($request->comment['author'] == null || $request->comment['author'] == null){
            return response()->json(['error' => 'Author and Content cannot be empty']);
        }
        $post = 2;

        $comment = Comment::create([
            'author' => $request->comment['author'],
            'content'=> $request->comment['content'],
            'email' => $request->comment['email']
        ]);

        $comment->posts()->attach($post);
        return response()->json($comment);
        
        
        return response()->json('ok');
    }
}

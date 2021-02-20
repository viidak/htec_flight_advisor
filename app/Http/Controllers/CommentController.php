<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'new_comment' => 'required|string|max:255',
            'city_id' => 'required|integer|numeric|min:1'
        ]);

        Comment::create([
            'description' => $request->new_comment,
            'user_id' => auth()->user()->id,
            'city_id' => $request->city_id
        ]);

        return response()->json(['message' => 'Comment added successfully'], 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $msg = '';
        $comment = Comment::find($id);
        if($comment) {
            if ($comment->user_id == auth()->user()->id) {
                Comment::where('id', $id)->update(array('description' => $request->text));
                $msg = 'Comment was updated.';
                $code = 204;
            }
            $msg = 'Current user cannot edit this comment.';
            $code = 401;
        } else {
            $msg = 'Couldn\'t find comment with that ID.';
            $code = 404;
        }
        return response()->json(['message' => $msg], $code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $msg = '';
        $comment = Comment::find($id);
        if ($comment) {
            if ($comment->user_id == auth()->user()->id) {
                $comment->delete();
                $msg = 'Comment deleted successfully.';
                $code = 204;
            } else {
                $msg = 'Current user cannot delete this comment.';
                $code = 401;
            }
        } else {
            $msg = 'Couldn\'t find comment with that ID.';
            $code = 404;
        }
        return response()->json(['message' => $msg], $code);
    }
}

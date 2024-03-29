<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index(){
        $data = [
            'messages' => Post::with('user')->latest()->get()
            //'messages' => Post::orderBy('created_at','desc')->get()
        ];

        return view('posts.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required','max:255']
        ]);

        // Esta seria la manera clasica de crear un mensaje, pero en seguida se muestra la manera usando la relacion del usuario con sus mensajes
/*        Celula::create([
            'message' => $request->get('message'),            
            'user_id' => auth()->id(),
        ]);
*/      
        $request->user()->posts()->create($validated);
        session()->flash('status','Mensaje agregado!!');
        return to_route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post){
        $this->authorize('update',$post);
        return view('posts.edit',['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post){
        $this->authorize('update',$post);
        $validated = $request->validate([
            'message' => ['required','max:255']
        ]);        
        $post->update($validated);        
        return to_route('posts.index')->with('status','Mensaje actualizado!!');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post){
        $this->authorize('delete',$post);
        $post->delete();
        return to_route('posts.index')->with('status','Mensaje Eliminado');
    }
}

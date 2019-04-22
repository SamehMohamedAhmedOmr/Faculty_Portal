<?php

namespace App\Http\Controllers;

use App\Album;
use App\AlbumImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = Album::all();
        $first = Album::all()->first();
//        dd($first);
        if($first!=null)
            $images = AlbumImage::all()->where('album_id',$first->id);
        $func = 1;
        return view ('Portal.public.gallery',compact('albums','images', 'func'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if($user->userable_type == 'Adm')
            return view('Portal.admin_panel.addAlbum');
        else
            return view('Portal.public.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50|min:5|string|regex:/^[a-zA-Z0-9\s\-\)\(\[\]]+$/u', // start with letter
        ],
            [
                'name.required' => 'Please write a name',
                'name.regex' => 'Please write a valid name (letters and numbers only)',
                'name.max'=>'Name must be between 5 and 50 character',
                'name.max'=>'Name must be between 10 and 50 character'
            ]);
        $album = new Album();
        $album->name = $request->name;
        if($album->save())
        {
            return redirect()->action('AlbumController@index');
        }
        else{
            App::abort(500, 'Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Album::FindOrFail($id)){
            $albums = Album::all();
            $images = AlbumImage::all()->where('album_id',$id);
        }
        $func = 0;
        return view('Portal.public.gallery',compact('images','albums','func'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if ($user->userable_type == 'Adm')
        {
            $album = Album::findOrFail($id);
            return view('Portal.admin_panel.editAlbum',compact('album'));
        }
        else
            return view('Portal.public.index');
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
        $this->validate($request, [
            'name' => 'required|max:50|min:5|string|regex:/^[a-zA-Z0-9\s\-\)\(\[\]]+$/u', // start with letter
        ],
            [
                'name.required' => 'Please write a name',
                'name.regex' => 'Please write a valid name (letters and numbers only)',
                'name.max'=>'Name must be between 5 and 50 character',
                'name.max'=>'Name must be between 10 and 50 character'
            ]);
        $album = Album::findOrFail($id);
        $album->name = $request->name;
        if($album->save())
        {
            return redirect()->action('AlbumController@index')->with('message', 'Album has been updated successfully!');
        }
        else{
            App::abort(500, 'Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        dd( "here");
        $Auth_user = Auth::user();
        if($Auth_user->userable_type == 'Adm') {

            $album = Album::findOrFail($id);
            $album->delete();
            return redirect()->action('AlbumController@index')->with('message', 'Album has been deleted successfully!');
//            return "yes";
        }
        else
        {  return view('Portal.public.index'); }
    }
}

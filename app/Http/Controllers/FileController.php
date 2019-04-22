<?php

namespace App\Http\Controllers;

use App\Album;
use App\AlbumImage;
use App\Material;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function DoctorUploadMaterial(Request $request)
    {
        if(Auth::user()->userable_type != 'Doc')
        {return view('Portal.public.index');}

        if($request->hasFile('file'))
        {
            $this->validate($request,[
               'file'=> 'required|file|max:10000',
                'semesterID'=>'required|numeric',
                'subjectID' => 'required|numeric',
                'description' => 'required|string'
            ]);
            $now = Carbon::now();
            /*Store File in Storage*/
            $fileName = $now.'__'.$request->file->getClientOriginalName();
            $request->file->storeAs('public/DoctorUploads/',$fileName);
            $material = new Material();

            $material->date=$now;
            $material->semester_id=$request->semesterID;
            $material->subject_id=$request->subjectID;
            $material->doctor_id=Auth::user()->userable_id;
            $material->file=$fileName;
            $material->description=$request->description;
            $material->save();
            return redirect()->back()->with('message' , ' file uploaded Successfully');
        }
        else
        {
            return redirect()->back()->withErrors(['Error'=>' can\'t store an empty file ']);
        }
    }

    public function DoctorRemoveFile (Request $request)
    {
        if(Auth::user()->userable_type != 'Doc')
        {return view('Portal.public.index');}

        $file = Material::where([ ['semester_id', $request->semesterID], ['subject_id',$request->subjectID] ,['doctor_id', Auth::user()->userable_id ],['date',$request->dateTime]])->first();
        if($file!==null)
        {
            Storage::delete('DoctorUploads/'.$request->fileName);
            DB::table('materials')->where([ ['semester_id', $request->semesterID], ['subject_id',$request->subjectID] ,['doctor_id', Auth::user()->userable_id ],['date',$request->dateTime] ])->delete();
            return redirect()->back()->with('message','File Deleted Successfully');
        }
        else
        {
            return redirect()->back()->withErrors(['Error'=>'can\'t delete an empty file or file not exists']);
        }
    }


    public function uploadForm($id)
    {
        $Auth_user = Auth::user();
        if($Auth_user->userable_type == 'Adm')
        {
            $album = Album::all()->where('id',$id)->first();
            return view('Portal.admin_panel.upload_form',compact('album'));
        }
        else
            return view('Portal.public.gallery');
    }

    public function uploadSubmit(Request $request)
    {
        $this->validate($request, [
            'photo' => 'required|file|max:2000',
        ]);
        $type = $request->file('photo')->clientExtension();
        $ext = array('png','jpg','jpeg','gif','bmp');

        if(in_array($type,$ext)){
            $img = $request->file('photo');
            $input['filename'] = time().'.'. $img->getClientOriginalName();
            $dest = public_path('images/frontend/gallery');
            $img->move($dest,$input['filename']);

            AlbumImage::create([
                'album_id' => $request->id,
                'filename' => $input['filename']
            ]);
            return redirect()->action('AlbumController@index')->with('message', 'Photo added successfully');
        }
    }
    public function removePhoto($id)
    {
        try
        {
            $Auth_user = Auth::user();
            if($Auth_user->userable_type == 'Adm')
            {
                $img = AlbumImage::findOrFail($id);
                $album_id = $img->album_id;
                $img->delete();
                $imagesInAlbum = AlbumImage::where('album_id',$album_id)->get();
                if(!sizeof($imagesInAlbum)>0)
                {
                    $album = Album::where('id',$album_id)->delete();
                    return redirect()->action('AlbumController@index');
                }
                return redirect()->back()->with('message' , 'Photo deleted successfully');
            }
            else
            {return redirect()->action('AlbumController@index');}
        }
        catch(ModelNotFoundException $e)
        { return view('Portal.public.index'); }
    }


//    public function InstructorUploadMaterial (Request $request)
//    {
//        if(Auth::user()->userable_type != 'T_A')
//        {return view('Portal.public.index');}
//
//        if($request->hasFile('file'))
//        {
//            $this->validate($request,[
//                'file'=> 'required|file|max:10000',
//                'semesterID'=>'required|numeric',
//                'subjectID' => 'required|numeric',
//                'description' => 'required|string'
//            ]);
//            $now = Carbon::now();
//            /*Store File in Storage*/
//            $fileName = $now.'__'.$request->file->getClientOriginalName();
//            $request->file->storeAs('public/DoctorUploads/',$fileName);
//            $material = new Material();
//
//            $material->date=$now;
//            $material->semester_id=$request->semesterID;
//            $material->subject_id=$request->subjectID;
//            $material->doctor_id=Auth::user()->userable_id;
//            $material->file=$fileName;
//            $material->description=$request->description;
//            $material->save();
//            return redirect()->back()->with('message' , ' file uploaded Successfully');
//        }
//        else
//        {
//            return redirect()->back()->withErrors(['Error'=>' can\'t store an empty file ']);
//        }
//    }




}

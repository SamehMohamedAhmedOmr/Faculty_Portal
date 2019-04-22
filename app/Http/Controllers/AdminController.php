<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Requests\UserRequest;
use App\User;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user = Auth::user();
        if($user->userable_type == 'Adm') {
            $list = Auth::user()->where('userable_type','=','Adm')->paginate(15);
            return view('Portal.admin_panel.Panel',compact('list'));
        }
        else
        {return view('Portal.public.index');}
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
        { return view('Portal.admin_panel.Panel'); }
        else
        {return view('Portal.public.index');}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $limit = Carbon::parse(today())->addWeek(1)->format('Y-m-d');
        $this->validate($request, [
            'email'=>'unique:users',
            'password'=>'required|max:30|min:8',
            'national_id' => 'unique:users,national_id',
            'working_hours' => 'required|numeric|between:6,12',
            'hire_date'=>'required|date|after_or_equal:today|before:'.$limit
        ],
            [
                'password.required'  => 'Password is required',
                'working_hours.required'  => 'Number of working hours is required',
                'hire_date.required'  => 'Hire date is required',

                'password.max'  => 'Password must be between 8 and 30 characters',
                'password.min'  => 'Password must be between 8 and 30 characters',
                'email.unique'  => 'This email already exists',
                'national_id.unique'  => 'The national ID already exists',
                'working_hours.between'  => 'Number of working hours must be between 6 and 12 hours',
                'working_hours.numeric'  => 'Please enter valid number of working hours',
                'hire_date.date'  => 'Please enter valid date of hiring',
                'hire_date.after_or_equal'  => 'Please enter valid date of hiring - after today',
                'hire_date.before'  => 'Please enter valid date of hiring - before a week from now',
            ]);

        /*get Last id of user*/
        $lastID = DB::table('users')->orderBy('userable_id', 'DESC')->first();
        $lastID= $lastID->userable_id+1;

        $user = new Admin();
        $user->working_hours = $request->working_hours;
        $user->hire_date = $request->hire_date;
        $user->id = $lastID;

        if($user->save())
        {
            $data = Response::json($user->id);
            User::Create(
                [
                    'userable_id' => $data->original ,'userable_type'=>'Adm',
                    'name_ar' => $request->name_ar ,'name_en' => $request->name_en ,
                    'email' => $request->email , 'password' => bcrypt($request->password) ,
                    'national_id' => $request->national_id , 'address' => $request->address ,
                    'phone' => $request->phone ,
                    'DOB' => $request->DOB , 'gender' => $request->gender
                ]);
            return redirect()->action('AdminController@index')->with('message', 'Admin added successfully');
        }
        else
        {App::abort(500, 'Error');}
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            if($request->search !== 'non')
                $admins = User::where('name_en', 'LIKE', '%' . $request->search . "%")->where('userable_type', '=', 'Adm')->where('userable_id', '!=', '20161810')->get();
            else
                $admins = User::where('userable_type', '=', 'Adm')->where('userable_id', '!=', '20161810')->get();
            if ($admins) {
                foreach ($admins as $key => $adm) {
                    $output .= '<tr>' .
                        '<td>' . $adm->userable_id . '</td>' .
                        '<td>' . $adm->name_en . '</td>' .
                        '<td>' . $adm->email . '</td>' .
                        '<td>' . $adm->phone . '</td>' .
                        '<td>
                                <form method="GET" action="/Panel/Admin/' . $adm->userable_id . '/edit">
                                    <button type="submit" class="btn btn-info EditButton"> Edit
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </form>
                            </td>
                            <meta name="csrf-token" content="' . csrf_token() . '">                          
                        </tr>';
                }
                if (!$output)
                {
                    $output = "<tr>
                                    <td colspan=\"6\">Not Found</td>
                                  </tr>";
                }
                return Response($output);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try
        {
            $Auth_user = Auth::user();
            if($Auth_user->userable_type == 'Adm')
            {
                $user = Admin::findOrFail($id);
                $basics = $user->user;
                return view('Portal.admin_panel.Panel', compact('user', 'basics'));
            }
            else
            {return view('Portal.public.index');}
        }
        catch(ModelNotFoundException $e)
        {
            return redirect()->action('AdminController@index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $this->validate($request, [
            'email' => 'unique:users,email,'. $id.',userable_id',
            'national_id' => 'unique:users,national_id,'. $id.',userable_id',
            'working_hours' => 'required|numeric|between:6,12',
        ],
            [
                'working_hours.required'  => 'Number of working hours is required',
                'email.unique'  => 'This email already exists',
                'national_id.unique'  => 'The national ID already exists',
                'working_hours.between'  => 'Number of working hours must be between 6 and 12 hours',
                'working_hours.numeric'  => 'Please enter valid number of working hours',
            ]);

        $adm = User::where('userable_id',$id)->get()->first();
        $adm->address = $request->address;
        $adm->phone = $request->phone;
        $adm->name_ar = $request->name_ar;
        $adm->name_en = $request->name_en;
        $adm->email = $request->email;
        $adm->gender = $request->gender;
        $adm->DOB = $request->DOB;
        $adm->userable->working_hours = $request->working_hours;
        if($adm->save())
        {
            $object = $adm->userable;
            $object->save();
            return redirect()->action('AdminController@index')->with('message', 'Admin updated successfully');
        }
        else
        {  App::abort(500, 'Error'); }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $Auth_user = Auth::user();
            if($Auth_user->userable_type == 'Adm')
            {
                $adm = Admin::findOrFail($id);
                $adm->delete();

                $user = User::findOrFail($id);
                $user->delete();
                return response()->json([
                    'success' => 'Admin has been deleted successfully!'
                ]);
            }
            else
            {return view('Portal.public.index');}
        }
        catch(ModelNotFoundException $e)
        { return view('Portal.public.index'); }
    }
}

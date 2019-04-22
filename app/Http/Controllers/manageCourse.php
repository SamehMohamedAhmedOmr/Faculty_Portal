<?php

namespace App\Http\Controllers;

use App\Department;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class manageCourse extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Subject::paginate(10);
        return view('Portal.admin_panel.Panel',compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $courses = Subject::all();
        return view('Portal.admin_panel.Panel',compact('departments','courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'          => 'required|string|max:100|min:5',
            'Description'   => 'required|max:800|min:10',
            'Prerequisite'  => 'nullable|exists:subjects,id',
            'Department'    => 'required|exists:departments,id',
            'Credit'        => [
                'required',
                Rule::in([2, 3])
            ],
            'Final_Grade'   => [
                'required',
                Rule::in([50, 60])
            ],
            'level'         => [
                'required',
                Rule::in([1, 2, 3, 4])
            ]
        ]);

        $practical = 0;
        if($request->Final_Grade == 50){
            $practical=1;
        }

        Subject::create(['name'=>$request->name,
                         'description'=>$request->Description,
                         'final_grade'=>$request->Final_Grade,
                         'level_req'=>$request->level,
                         'practical'=>$practical,
                         'credit_hours'=>$request->Credit,
                         'prerequisite'=>$request->Prerequisite,
                         'department_id'=>$request->Department,
                         'admin_id'=>Auth::user()->userable_id]);

       $request->session()->flash('Add_Courses_Success','Course add correctly');
       return redirect()->action('manageCourse@index');
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
                $subjects = Subject::where('name', 'LIKE', '%' . $request->search . "%")->get();
            else
                $subjects = Subject::all();
            if ($subjects) {
                foreach ($subjects as $key => $sbj){
                    $output .=
                        '<tr>' .
                        '<td>' . ++$key . '</td>' .
                        '<td>' . $sbj->name . '</td>' .
                        '<td>' . $sbj->credit_hours . '</td>' .
                        '<td>' . $sbj->level_req . '</td>' .
                        '<td>' . $sbj->department->name. '</td>'.
                        '<td>' . (($sbj->practical==0)?'No':'Yes') .'</td>' .
                        '<td>'.
                        '<form id="formey" action="/Panel/manageCourses/'.$sbj->id.'/edit" method="get" ><a href="javascript:{}" onclick="document.getElementById(\'formey\').submit();"><i class="fas fa-edit" aria-hidden="true"></i></a><meta name="csrf-token" content="' . csrf_token() . '"></form>'.
                        '</td>' .
                        '</tr>';
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
        try{
            $departments = Department::all();
            $courses = Subject::all();
            $selected = Subject::findOrFail($id);
            return view('Portal.admin_panel.Panel',compact('selected','departments','courses'));
        }catch (\Exception $e){
            session()->flash('No_Course','No Course by that Name');
            return redirect()->action('manageCourse@index');
        }
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
        try{
            $updatedSubject = Subject::findOrFail($id);
        }catch (\Exception $e){
            session()->flash('No_Course','No Course by that Name');
            return redirect()->action('manageCourse@index');
        }

        $this->validate($request,[
            'name'          => 'required|string|max:100|min:5',
            'Description'   => 'required|max:800|min:10',
            'Prerequisite'  => 'nullable|exists:subjects,id',
            'Department'    => 'required|exists:departments,id',
            'Credit'        => ['required', Rule::in([2, 3])],
            'finalGrade'    => ['required', Rule::in([50, 60])],
            'level'         => ['required', Rule::in([1, 2, 3, 4])]
        ]);

        $practical = 0;
        if($request->Final_Grade == 50){
            $practical=1;
        }

        $updatedSubject->name            = $request->name;
        $updatedSubject->Description     = $request->Description;
        $updatedSubject->final_grade     = $request->finalGrade;
        $updatedSubject->level_req       = $request->level;
        $updatedSubject->practical       = $practical;
        $updatedSubject->credit_hours    = $request->Credit;
        $updatedSubject->prerequisite    = $request->Prerequisite;
        $updatedSubject->department_id   = $request->Department;
        $updatedSubject->admin_id        = Auth::user()->userable_id;

        $updatedSubject->save();

        $request->session()->flash('update_Course_success','Course Edited Successfully');
        return redirect()->action('manageCourse@index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}

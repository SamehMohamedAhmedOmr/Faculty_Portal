<?php

namespace App\Http\Controllers;

use App\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class evaluate_course extends Controller
{

    public function selectUnEvaluate(){
        $student = Auth::user();
        $currentSemester = Semester::where('complete',1)->get()->sortBy('id');
        if(count($currentSemester)>0) {
            $timetables = $student->userable->timetables->where('semester_id', $currentSemester[count($currentSemester) - 1]->id)
                ->where('timetableable_type', 'Doc')->sortByDesc('subject_id');

            $evaluatedCourses = DB::table('student_rate')->select('subject_id')
                ->where('semester_id', $currentSemester[count($currentSemester) - 1]->id)->where('student_id', $student->userable->id)->get();

            $selectedCourses = [];
            if ($evaluatedCourses->count() > 0) {
                foreach ($timetables as $timetable) {
                    $flag = 0;
                    foreach ($evaluatedCourses as $evaluatedCourse) {
                        if ($timetable->subject_id === $evaluatedCourse->subject_id) {
                            $flag = 0;
                            break;
                        } else {
                            $flag = 1;
                        }
                    }
                    if ($flag === 1) {
                        $selectedCourses[] = $timetable;
                    }
                }
            } else {
                $selectedCourses = $timetables;
            }
        }
        else{
            $selectedCourses = null;
        }
        return $selectedCourses;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selectedCourses= $this->selectUnEvaluate();
        $currentSemester = Semester::where('complete',1)->get()->sortBy('id');
        if($selectedCourses != null){
            $check = true;
            return view('Portal.student_panel.Panel',compact('selectedCourses','currentSemester','check'));
        }
        $check = false;
        return view('Portal.student_panel.Panel',compact('selectedCourses','currentSemester','check'));
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
        $this->validate($request,[
            'selectedCourse' => 'required|exists:subjects,id',
            'survey1'        => [
                'required', Rule::in([1,2,3,4,5])
            ],
            'survey2'        => [
                'required', Rule::in([1,2,3,4,5])
            ],
            'survey3'        => [
                'required', Rule::in([1,2,3,4,5])
            ],
            'survey4'        => [
                'required', Rule::in([1,2,3,4,5])
            ],
            'survey5'        => [
                'required', Rule::in([1,2,3,4,5])
            ],
            'survey6'        => [
                'required', Rule::in([1,2,3,4,5])
            ]
        ],
            [
                'survey1.required' => 'Question 1 is required',
                'survey2.required' => 'Question 2 is required',
                'survey3.required' => 'Question 3 is required',
                'survey4.required' => 'Question 4 is required',
                'survey5.required' => 'Question 5 is required',
                'survey6.required' => 'Question 6 is required',
                'survey1.in'       => 'You Should select one of the options of Question 1',
                'survey2.in'       => 'You Should select one of the options of Question 2',
                'survey3.in'       => 'You Should select one of the options of Question 3',
                'survey4.in'       => 'You Should select one of the options of Question 4',
                'survey5.in'       => 'You Should select one of the options of Question 5',
                'survey6.in'       => 'You Should select one of the options of Question 6'
            ]);

        $selectedCourses= $this->selectUnEvaluate();

        $check = false;
        foreach ($selectedCourses as $selected){

            if($request->selectedCourse == $selected->subject_id){
                $check = true;
                break;
            }
        }
        if($check === false){
            return redirect()->back()->withErrors(['error'=>'Please re-submit']);
        }
        else{
            $total = $request->survey1 + $request->survey2 + $request->survey3 + $request->survey4
                + $request->survey5 + $request->survey6;

            $avg = $total/6;

            $suitableAvg = round($avg,1);

            $student = Auth::user();
            $currentSemester = Semester::where('complete',1)->get()->sortBy('id');

            DB::table('student_rate')->insert(
                [
                    'student_id'  => $student->userable->id ,
                    'semester_id' => $currentSemester[count($currentSemester)-1]->id,
                    'subject_id'  => $request->selectedCourse,
                    'rate'        => $suitableAvg
                ]);

            session()->flash('EvaluateSuccessfully','Your Evaluate Successfully submit');
            return redirect()->action('evaluate_course@index');
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
        //
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

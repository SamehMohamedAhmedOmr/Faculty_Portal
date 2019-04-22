<?php

namespace App\Http\Controllers;

use App\Place;
use http\Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


class managePlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $places = Place::paginate(10);
        return view('Portal.admin_panel.Panel',compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Portal.admin_panel.Panel');
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
            'name' => 'required|string|max:100|min:5',
            'seats' => 'required|integer|max:100|min:10',
            'type' => [
                'required',
                Rule::in([0, 1])
            ]
        ]);

        Place::create(['name'=>$request->name,
                       'seats'=>$request->seats,
                       'type'=>$request->type,
                       'admin_id'=>Auth::user()->userable_id]);

        $request->session()->flash('Add_Place_Success','Place add correctly');
        return redirect()->action('managePlacesController@index');
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
                $places = Place::where('name', 'LIKE', '%' . $request->search . "%")->get();
            else
                $places = Place::all();
            if ($places) {
                foreach ($places as $key => $place){
                    $output .=
                        '<tr>' .
                        '<td>' . ++$key . '</td>' .
                        '<td>' . $place->name . '</td>' .
                        '<td>' . (($place->type==0)?'Hall':'Lab') .'</td>' .
                        '<td>' . $place->seats . '</td>' .
                        '<td>'.
                        '<form id="formey" action="/Panel/managePlaces/'.$place->id.'/edit" method="get" ><a href="javascript:{}" onclick="document.getElementById(\'formey\').submit();"><i class="fas fa-edit" aria-hidden="true"></i></a><meta name="csrf-token" content="' . csrf_token() . '"></form>'.
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
            $selected = Place::findOrFail($id);
            return view('Portal.admin_panel.Panel',compact('selected'));
        }catch (\Exception $e){
            session()->flash('no_Place','There no place by that Name');
            return redirect()->action('managePlacesController@index');
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
            $updatedPlace = Place::findOrFail($id);
            $this->validate($request,[
                'name' => 'required|string|max:100|min:5',
                'seats' => 'required|integer|max:100|min:10',
                'type' => [
                    'required',
                    Rule::in([0, 1])
                ]
            ]);
            $updatedPlace->name      = $request->name;
            $updatedPlace->seats     = $request->seats;
            $updatedPlace->type      = $request->type;
            $updatedPlace->admin_id  = Auth::user()->userable_id;

            $updatedPlace->save();

            $request->session()->flash('update_Place_Success','Place updated successfully');
            return redirect()->action('managePlacesController@index');
        }catch (\Exception $e){
            session()->flash('no_Place','There no place by that Name');
            return redirect()->action('managePlacesController@index');
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
        //
    }
}

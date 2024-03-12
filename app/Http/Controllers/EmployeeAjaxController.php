<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use DataTables;


class EmployeeAjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Employee::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('country_id', function ($row) {

                    return $row->country->name ?? '';
                })
                ->editColumn('state_id', function ($row) {

                    return $row->state->name ?? '';
                })

                ->editColumn('image', function ($row) {

                    if ($row->image != '') {
                        return ' <img src="' . asset('/images/' . $row->image) . '" alt="profile Pic" height="200" width="200">';
                    }
                    return '';
                })

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editEmployee">Edit</a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteEmployee">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        $data['countries'] = Country::get(["name", "id"]);

        return view('employeeAjax', $data);
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
            'name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'state_id' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            Employee::updateOrCreate(
                [
                    'id' => $request->employee_id
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'image' => $imageName
                ]
            );
        } else {
            Employee::updateOrCreate(
                [
                    'id' => $request->employee_id
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                ]
            );
        }



        return response()->json(['success' => 'Employee saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Employee::find($id);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employee::find($id)->delete();

        return response()->json(['success' => 'Employee deleted successfully.']);
    }

    public function fetchState(Request $request)
    {
        $data['states'] = State::where("country_id", $request->country_id)
            ->get(["name", "id"]);

        return response()->json($data);
    }
}

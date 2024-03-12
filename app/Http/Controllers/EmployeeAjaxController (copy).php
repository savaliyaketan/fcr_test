<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Country;

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
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
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
        Employee::updateOrCreate(
            [
                'id' => $request->product_id
            ],
            [
                'name' => $request->name,
                'detail' => $request->detail
            ]
        );

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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

use App\Department;
use App\Item;
use App\Samogri;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $department = Department::create([
            'name'=>$request->name
        ]);

        $departments = Department::orderBy('name', 'asc')->get();
        return Response::json(['departments'=>$departments, 'msg'=>'Departments Add Successfully']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $department = Department::with('items')->find($id);
        return Response::json(['name'=>$department->name, 'btn1'=>'<button type="button" class="btn btn-success" id="save_dep">Save Department</button>', 'btn2'=>'<button type="button" class="btn btn-danger" id="delete_dep">Delete Department</button>', 'btn3'=>'<button type="button" class="btn btn-primary" id="create_item">Add Items</button>', 'items'=>$department->items]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $department = Department::where('id', $id)->update([
            'name'=>$request->name
        ]);

        $departments = Department::orderBy('name', 'asc')->get();
        return Response::json(['departments'=>$departments, 'btn1'=>'<button type="button" class="btn btn-primary" id="create_dep">Add Department</button>', 'msg'=>'Departments Update Successfully']);
    }

    public function destroy($id)
    {
        $items = Item::where('department_id', $id)->get();
        foreach ($items as $item) {
            Samogri::where('item_id', $item->id)->delete();
        }
        
        Item::where('department_id', $id)->delete();
        Department::where('id', $id)->delete();
        $departments = Department::orderBy('name', 'asc')->get();
        return Response::json(['departments'=>$departments, 'btn1'=>'<button type="button" class="btn btn-primary" id="create_dep">Add Department</button>', 'msg'=>'Departments Delete Successfully']);

    }
}

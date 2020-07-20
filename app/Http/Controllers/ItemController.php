<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department;

use App\Item;

use App\Samogri;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $departments = Department::orderBy('name', 'asc')->get();
        $items = Item::orderBy('name', 'asc')->get();
        return view('admin/items', ['departments' => $departments, 'items' => $items]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'department' => 'required|numeric',
            'name' => 'required|string',
            'size_weight' => 'required|numeric',
            'sw_unit' => 'required|string',
            'quantity' => 'required|numeric',
            'q_unit' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $item = Item::create([
            'department_id'=>$request->department,
            'name' => $request->name,
            'size_weight' => $request->size_weight,
            'sw_unit' => $request->sw_unit,
            'quantity' => $request->quantity,
            'q_unit' => $request->q_unit,
            'price' => $request->price,
        ]);

        $items = Item::where('department_id', $request->department)->orderBy('name', 'asc')->get();
        return Response::json(['items'=>$items, 'msg'=>'Items Add Successfully']);
    }

    public function edit($id)
    {
         $item = Item::find($id);
         
         return Response::json(['itmName' =>$item->name, 'itmSW' =>$item->size_weight, 'itmSWunit' =>$item->sw_unit, 'itmQty' =>$item->quantity, 'itmQunit' =>$item->q_unit, 'itmPrice' =>$item->price, 'btn1' => '<button type="button" class="btn btn-success" id="save_item">Save Item</button>', 'btn2' => '<button type="button" class="btn btn-danger" id="delete_item">Delete Item</button>']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department' => 'required|numeric',
            'name' => 'required|string',
            'size_weight' => 'required|numeric',
            'sw_unit' => 'required|string',
            'quantity' => 'required|numeric',
            'q_unit' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $item = Item::where('id', $id)->update([
            'name' => $request->name,
            'size_weight' => $request->size_weight,
            'sw_unit' => $request->sw_unit,
            'quantity' => $request->quantity,
            'q_unit' => $request->q_unit,
            'price' => $request->price,
        ]);

        $items = Item::where('department_id', $request->department)->orderBy('name', 'asc')->get();
        return Response::json(['items'=>$items, 'btn1' => '<button type="button" class="btn btn-primary" id="create_item">Add Item</button>', 'msg'=>'Item Update Successfully']);
    }

    public function destroy($id)
    {
        $data = explode('-', $id);
        Samogri::where('item_id', $data[1])->delete();
        Item::where('id', $data[1])->delete();
        $items = Item::where('department_id', $data[0])->orderBy('name', 'asc')->get();
        return Response::json(['items'=>$items, 'btn1' => '<button type="button" class="btn btn-primary" id="create_item">Add Item</button>','msg'=>'Item Delete Successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Level;

use App\Seller;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allLevels = Level::orderBy('name', 'asc')->get();
        $allSellers = Seller::orderBy('name', 'asc')->get();
        return view('admin/seller', ['sellers' => $allSellers, 'levels' => $allLevels]);
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string|unique:sellers',
            'level_id' => 'required|numeric',
            'address' => 'nullable|string',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            //return Response::json(array('errors'=>$validator->getMessageBag()));
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $seller = Seller::create([
                'name' => $request->input('name'),
                'level_id' => $request->input('level_id'),
                'address' => $request->input('address'),
            ]);

            $allSellers = Seller::orderBy('name', 'asc')->get();
            return Response::json(['sellers'=>$allSellers, 'msg'=>'Seller Add Successfully']);
        }
    }

    public function edit(Request $request)
    {

        $sellers = Seller::where('id', $request->id)->get();
        foreach($sellers as $record){
            $level_id = $record->level_id;
            $name = $record->name;
            $address = $record->address;
        }

        return Response::json(['level' => $level_id, 'name' => $name, 'address' => $address]);
    }

    public function update(Request $request)
    {
        $rules = array(
            'name' => 'required|string',
            'level_id' => 'required|numeric',
            'address' => 'nullable|string',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $seller = Seller::where('id', $request->input('id'))->update(['name' => $request->input('name'), 'level_id'=>$request->input('level_id'), 'address' => $request->input('address')]);

            $allSellers = Seller::orderBy('name', 'asc')->get();
            return Response::json(['sellers'=>$allSellers, 'msg'=>'Seller Update Successfully']);
        }
    }

    public function delete(Request $request)
    {
        $rules = array(
            'id' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $seller = Seller::where('id', $request->input('id'))->delete();
            $allSellers = Seller::orderBy('name', 'asc')->get();
            return Response::json(['sellers'=>$allSellers, 'msg'=>'Seller Delete Successfully']);
        }
    }
}

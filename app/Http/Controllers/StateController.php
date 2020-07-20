<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\State;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        //$allStates = State::orderBy('name', 'asc')->get();
        $allStates = State::all();
        return view('admin/state', ['states' => $allStates]);
    }

    public function showState(Request $request)
    {
        $states = State::where('id', $request->state_id)->get();
        foreach ($states as $state) {
            $name = $state->name;
            $phone = $state->phone;
            $address = $state->address;
        }
        return Response::json(['name'=>$name, 'phone'=>$phone, 'address'=>$address]);
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $state = State::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            $allStates = State::orderBy('name', 'asc')->get();
            return Response::json(['states'=>$allStates, 'msg'=>'State Add Successfully']);
        }
    }

    public function update(Request $request)
    {
        $rules = array(
            'id' => 'required|numeric',
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $State = State::where('id', $request->input('id'))->update(['name' => $request->name, 'phone' => $request->phone, 'address' => $request->address,]);
            $allStates = State::orderBy('name', 'asc')->get();
            return Response::json(['states'=>$allStates, 'msg'=>'States Save Successfully']);
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
            $State = State::where('id', $request->input('id'))->delete();
            $allStates = State::orderBy('name', 'asc')->get();
            return Response::json(['states'=>$allStates, 'msg'=>'State Delete Successfully']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Level;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $allLevels = Level::orderBy('name', 'asc')->get();
        return view('admin/level', ['levels' => $allLevels]);
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|regex:/(^[A-Za-z ]+$)+/|unique:levels',
            'commission' => 'required|numeric|min:1|max:99',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            //return Response::json(array('errors'=>$validator->getMessageBag()));
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $level = Level::create([
                'name' => $request->input('name'),
                'commission' => $request->input('commission'),
            ]);

            $allLevels = Level::orderBy('name', 'asc')->get();
            return Response::json(['levels'=>$allLevels, 'msg'=>'Level Add Successfully']);
        }
    }

    public function edit(Request $request)
    {
        $levels = Level::where('id', $request->id)->get();

        foreach($levels as $record){
            $commission = $record->commission;
        }
        return Response::json(['commission' => $commission]);
    }

    public function update(Request $request)
    {
        $rules = array(
            'name' => 'required|regex:/(^[A-Za-z ]+$)+/',
            'commission' => 'required|numeric|min:1|max:99',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $level = Level::where('id', $request->input('id'))->update(['name' => $request->input('name')]);
            $allLevels = Level::orderBy('name', 'asc')->get();
            return Response::json(['levels'=>$allLevels, 'msg'=>'Level Save Successfully']);
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
            $level = Level::where('id', $request->input('id'))->delete();
            $allLevels = Level::orderBy('name', 'asc')->get();
            return Response::json(['levels'=>$allLevels, 'msg'=>'Level Delete Successfully']);
        }
    }

}

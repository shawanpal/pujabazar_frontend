<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Attribute;
use App\Category;
use App\Term;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categorys = Category::orderBy('position','asc')->get();
        // dd($category);
        // $allAttributes = Attribute::orderBy('name', 'asc')->get();
        return view('admin/attribute', ['categorys' =>$categorys]);
    }

    public function show(Request $request)
    {
        $attribute_id = $request->route('attribute_id');
        $terms = Term::where('attribute_id', '=', $attribute_id)->orderBy('name', 'asc')->get();
        return Response::json($terms);
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string',
            'category_id' => 'required',
            'subcategory_id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $attribut = Attribute::create([
                'name' => $request->input('name'),
                'subcategory_id' => $request->input('subcategory_id'),
                'category_id' => $request->input('category_id'),
            ]);

            $allAttributes = Attribute::orderBy('name', 'asc')->get();
            return Response::json(['attributes'=>$allAttributes, 'msg'=>'Attribute Add Successfully']);
        }
    }

    public function storeTerm(Request $request)
    {
        $rules = array(
            'attribute_id' => 'required|numeric',
            'name' => 'required|string',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(['errors'=>$validator->getMessageBag()]);
        }else{
            $check = Attribute::where('id',$request->input('attribute_id'))->first();

            if (is_null($check)) {
                return Response::json(['error'=>'Category is not exist']);
            }else{
                $url = str_replace(' ', '-', $request->name);
                $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
                $url = strtolower($url);
                Term::create([
                    'attribute_id' => $request->input('attribute_id'),
                    'name' => $request->input('name'),
                    'url' => $url
                ]);
                $allTerms = Term::where('attribute_id', $request->input('attribute_id'))->orderBy('name', 'asc')->get();
                return Response::json(['terms'=>$allTerms, 'msg'=>'Term Add Successfully']);
            }



        }
    }

    public function update(Request $request)
    {
        $rules = array(
            'name' => 'required|string'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $attribut = Attribute::where('id', $request->input('id'))->update(['name' => $request->input('name')]);
            $allAttributes = Attribute::orderBy('name', 'asc')->get();
            return Response::json(['attributes'=>$allAttributes, 'msg'=>'Attribute Save Successfully']);
        }
    }

    public function updateTerm(Request $request)
    {
        $rules = array(
            'id' => 'required|numeric',
            'attribute_id' => 'required|numeric',
            'name' => 'required|string',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(['errors'=>$validator->getMessageBag()]);
        }else{
            $url = str_replace(' ', '-', $request->name);
            $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
            $url = strtolower($url);
            Term::where('id', $request->input('id'))->update(['name' => $request->name, 'url' => $url]);
            $allTerms = Term::where('attribute_id', $request->input('attribute_id'))->orderBy('name', 'asc')->get();
            return Response::json(['terms'=>$allTerms, 'msg'=>'Term Save Successfully']);
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
            $attribut = Attribute::where('id', $request->input('id'))->delete();
            $allAttributes = Attribute::orderBy('name', 'asc')->get();
            return Response::json(['attributes'=>$allAttributes, 'msg'=>'Attribute Delete Successfully']);
        }
    }

    public function deleteTerm(Request $request)
    {
        $rules = array(
            'id' => 'required|numeric',
            'attribute_id' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            Term::where('id', $request->input('id'))->delete();
            $allTerms = Term::where('attribute_id', $request->input('attribute_id'))->orderBy('name', 'asc')->get();
            return Response::json(['terms'=>$allTerms, 'msg'=>'Term Delete Successfully']);
        }
    }

    public function getAttribute($catID,$subID){
        $attribut = Attribute::where('category_id',$catID)
        ->where('subcategory_id',$subID)
        ->get();
        $opt = '';
        foreach($attribut as $att){
            $opt.='<option value="'.($att->id).'">'.$att->name.'</option>';
        }
        return response()->json([
            'option' => $opt,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

use App\Page;

use Redirect;

use Session;

class PagesController extends Controller
{

    public function index()
    {
        return view('admin/pages');
    }

    public function show(Request $request)
    {
        if($request->name != ''){
            $pages = Page::where('name', $request->name)->get();
            $content = '';
            foreach ($pages as $page) {
                $content = $page->content;
            }
            return Response::json(['status'=>'success', 'content' => $content]);
        }else{
           return Response::json(['status'=>'error', 'content' => '']);
        }
    }

    public function update(Request $request)
    {
        $rules = array(
            'name' => 'required|string',
            'content' => 'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Redirect::back()->withInput()->withErrors($validator);
            //return view('admin/pages', ['errors' => $validator->getMessageBag()])->withInput();
            //return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $pages = Page::where('name', $request->name)->get();
            if(count($pages)>0){
                Page::where('name', $request->name)->update([
                    'content' => $request->content
                ]);
                Session::put('success', 'Page update successfully!!');
            }else{
                $page = Page::create([
                    'name' => $request->name,
                    'content' => $request->content
                ]);
                if($page->id){
                    Session::put('success', 'Page create successfully!!');
                }else{
                    Session::put('error','You have some error!!');
                }

            }

        }
        return redirect('admin/pages');
    }

}

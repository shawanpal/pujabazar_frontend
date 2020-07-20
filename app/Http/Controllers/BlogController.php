<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

use App\Blog;

use Redirect;

use Session;

class BlogController extends Controller
{

    public function index()
    {
        $blogs = Blog::all();

        return view('admin/blog', ['blogs' => $blogs]);
    }

    public function show(Request $request)
    {
        if($request->id != ''){
            $blogs = Blog::where('id', $request->id)->get();
            $heading = '';
            $published = '';
            $image = '';
            $content = '';
            foreach ($blogs as $blog) {
                $heading = $blog->heading;
                $published = $blog->published;
                $image = '<img src="'.asset('images/'.$blog->image).'" width="100%" height="100">';
                $content = $blog->content;
            }
            return Response::json(['status'=>'success', 'heading' => $heading, 'published' => $published, 'image' => $image, 'content' => $content]);
        }else{
            return Response::json(['status'=>'error', 'heading' => '', 'published' => '', 'image' => '', 'content' => '']);
        }
    }

    public function update(Request $request)
    {
        $rules = array(
            'heading' => 'required|string',
            'published' => 'required|date_format:Y-m-d',
            'image' => 'nullable',
            'image.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Redirect::back()->withInput()->withErrors($validator);
            //return view('admin/pages', ['errors' => $validator->getMessageBag()])->withInput();
            //return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $url = str_replace(' ', '-', $request->heading);
            $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
            $url = strtolower($url);
            if($request->id == ''){
                if($request->hasFile('image')){
                    $path = public_path('images/');
                    $file = $request->file('image');
                    $file_name = 'Blog_'.time().'.'.$file->getClientOriginalExtension();
                    $file->move($path, $file_name);
                    $blog = Blog::create([
                        'heading' => $request->heading,
                        'url' => $url,
                        'published' => $request->published,
                        'image' => $file_name,
                        'content' => $request->content
                    ]);
                    if($blog->id){
                        Session::put('success', 'Blog create successfully!!');
                    }else{
                        Session::put('error','You have some error!!');
                    }
                }else{
                    return Redirect::back()->withInput()->withErrors(['image' => 'The image field is required.']);
                }
            }else{
                if($request->hasFile('image')){
                    $image = Blog::where('id', $request->id)->get();
                    foreach ($image as $imag) {
                        @unlink(public_path().'/images/'.$imag->image);
                    }
                    $path = public_path('images/');
                    $file = $request->file('image');
                    $file_name = 'Blog_'.time().'.'.$file->getClientOriginalExtension();
                    $file->move($path, $file_name);
                    Blog::where('id', $request->id)->update([
                        'heading' => $request->heading,
                        'url' => $url,
                        'published' => $request->published,
                        'image' => $file_name,
                        'content' => $request->content
                    ]);
                }else{
                    Blog::where('id', $request->id)->update([
                        'heading' => $request->heading,
                        'url' => $url,
                        'published' => $request->published,
                        'content' => $request->content
                    ]);
                }
                Session::put('success', 'Blog update successfully!!');
            }
        }
        return redirect('admin/blog');
    }

    public function destroy(Request $request)
    {
        $blogs = Blog::where('id', $request->id)->get();
        foreach ($blogs as $blog) {
            $name = $blog->heading;
            @unlink(public_path().'/images/'.$blog->image);
        }


        $delet = Blog::where('id', $request->id)->delete();
        if($delet){
            return Response::json(['status'=>'success', 'msg'=>$name.' Delete Successfully']);
        }else{
            return Response::json(['status'=>'error', 'msg'=>$name.' can\'t Delete, Please try again']);
        }
    }
}

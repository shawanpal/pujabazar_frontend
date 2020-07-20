<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Auth;

use App\User;

class AccessController extends Controller
{
    public function index()
    {
        $users = User::where('role', '<>', 'Admin')->get();
        $acc = '';

        foreach ($users as $user) {
            $acc .= '<tr>
                        <td>'.$user->name.'</td>
                        <td>'.$user->email.'</td>
                        <td>'.$user->phone.'</td>
                        <td>'.$user->created_at.'</td>
                        <td>
                            <select id="user_stat'.$user->id.'">';
                            if($user->role=='Buyer'){
                                $acc .= '<option value="Buyer" selected>Buyer</option>
                                <option value="Seller">Seller</option>';
                            }else if($user->role=='Seller'){
                                $acc .= '<option value="Buyer">Buyer</option>
                                <option value="Seller" selected>Seller</option>';
                            }
                        $acc .= '</select>
                        </td>
                    </tr>';
        }
        return view('admin/access', ['access' => $acc]);
    }


    public function userRoll(Request $request)
    {
        if($request->user_roll==''){
            $users = User::where('id', $request->id)->get();
            foreach ($users as $user) {
                $user_roll = $user->role;
            }
            return Response::json(['status' => $user_roll]);
        }else{
           User::where('id', $request->id)->update(['role' => $request->user_roll]);
            $users = User::where('id', $request->id)->get();
            foreach ($users as $user) {
                $name = $user->name;
            }
            return Response::json(['status' => 'Success', 'msg'=> 'User roll successfully change for this User- '.$name]);
        }
    }


    public function destroy(Request $request)
    {
        //
    }
}

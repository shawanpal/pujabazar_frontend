<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\State;

use App\Pincode;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class PostcodeController extends Controller
{
    private $states;
    public function __construct()
    {
        $this->middleware('auth');
        $this->states = State::orderBy('name', 'asc')->get();
    }

    public function index()
    {
        $allPincodes = Pincode::orderBy('id', 'desc')->paginate(15);
        $pincodes = '';
        if(count($allPincodes)>0){
            foreach ($allPincodes as $pincode) {
                $pincodes .= '<tr>
                                <td>'.$pincode->pincode.'</td>
                                <td>'.$pincode->locality.'</td>
                                <td>'.$pincode->postOffice.'</td>
                                <td>'.$pincode->subDistrict.'</td>
                                <td>'.$pincode->district.'</td>
                                <td>'.$pincode->state.'</td>
                                <td>';

                    if($pincode->status == '1'){
                        $pincodes .= '<input type="checkbox" checked value="1" disabled>';
                    }else{
                       $pincodes .= '<input type="checkbox" value="1" disabled>';
                    }
                $pincodes .= '</td>
                    <td><button type="button" value="'.$pincode->id.'" class="btn btn-link edit_pincode"><i class="fa fa-pencil"></i></button></td>
                    <td><button type="button" value="'.$pincode->id.'" class="btn btn-link delete_pincode"><i class="fa fa-trash-o"></i></button></td>
                            </tr>';
            }
            $pincodes .= '<tr>
                            <td colspan="9" class="text-center">'.$allPincodes->links().'</td>
                        </tr>';
        }

        return view('admin/postcode', ['postcodes' => $pincodes, 'states' => $this->states]);
    }

    public function store(Request $request)
    {
        $rules = array(
            'locality' => 'nullable|string',
            'postOffice' => 'nullable|string',
            'pincode' => 'required|numeric',
            'subDistrict' => 'nullable|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'status' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            Pincode::create([
                'locality' => $request->locality,
                'postOffice' => $request->postOffice,
                'pincode' => $request->pincode,
                'district' => $request->district,
                'state' => $request->state,
                'status' =>$request->status

            ]);
            return Response::json(['msg'=>'Pincode Add Successfully']);
        }
    }

    public function edit(Request $request)
    {
        $allPincodes = Pincode::where('id', $request->id)->get();
        foreach ($allPincodes as $pin) {
            $id = $pin->id;
            $locality = $pin->locality;
            $postOffice = $pin->postOffice;
            $pincode = $pin->pincode;
            $subDistrict = $pin->subDistrict;
            $district =  $pin->district;
            $stat = $pin->state;
            if($pin->status == '1'){
                $status = '<input id="pin_status" type="checkbox" class="col form-control" name="status" checked value="1">';
            }else{
               $status = '<input id="pin_status" type="checkbox" class="col form-control" name="status" value="1">';
            }
        }

        $edit_form = '';
        $edit_form .= '<input type="hidden" id="pin_id" value="'.$id.'">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="pin_locality" class="col-md-4 col-form-label value-md-left">'.__('Village/Locality name').'</label>

                <div class="col-md-8">
                    <input id="pin_locality" type="text" class="form-control" name="locality" value="'.$locality.'">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="pin_postOffice" class="col-md-4 col-form-label value-md-left">'.__('Post Office').'</label>

                <div class="col-md-8">
                    <input id="pin_postOffice" type="text" class="form-control" name="postOffice" value="'.$postOffice.'">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="pin_pincode" class="col-md-4 col-form-label value-md-left">'.__('Pincode').'</label>

                <div class="col-md-8">
                    <input id="pin_pincode" type="text" class="form-control" name="pincode" value="'.$pincode.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="pin_subDistrict" class="col-md-4 col-form-label value-md-left">'.__('Sub District').'</label>

                <div class="col-md-8">
                    <input id="pin_subDistrict" type="text" class="form-control" name="subDistrict" value="'.$subDistrict.'">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="pin_district" class="col-md-4 col-form-label value-md-left">]'.__('District').'</label>

                <div class="col-md-8">
                    <input id="pin_district" type="text" class="form-control" name="district" value="'.$district.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="pin_state" class="col-md-4 col-form-label value-md-left">'.__('State').'</label>

                <div class="col-md-8">
                    <select id="pin_state" class="form-control" name="state" required autofocus>
                        <option value="">Select State</option>';
                        if(count($this->states) != 0){
                            foreach($this->states as $key => $st){
                                if(strtoupper($st->name) == $stat){
                                    $edit_form .= '<option value="'.strtoupper($st->name).'" selected>'.strtoupper($st->name).'</option>';
                                }else{
                                   $edit_form .= '<option value="'.strtoupper($st->name).'">'.strtoupper($st->name).'</option>';
                                }

                            }
                        }
                    $edit_form .= '</select>
                </div>
            </div>
        </div>

        <div class="col-md-6"></div>

        <div class="col-md-6 row">
            <label for="pin_status" class="col col-form-label value-md-left">'.__('Status (Service available or not)').'</label>';

            $edit_form .= $status;

        $edit_form .= '</div>';

        return Response::json(['editForm' => $edit_form]);
    }

    public function update(Request $request)
    {
        $rules = array(
            'locality' => 'nullable|string',
            'postOffice' => 'nullable|string',
            'pincode' => 'required|numeric',
            'subDistrict' => 'nullable|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'status' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            Pincode::where('id', $request->id)->update([
                'locality' => $request->locality,
                'postOffice' => $request->postOffice,
                'pincode' => $request->pincode,
                'subDistrict' => $request->subDistrict,
                'district' => $request->district,
                'state' => $request->state,
                'status' => $request->status

            ]);

            return Response::json(['msg'=>'Pincode update Successfully']);
        }
    }

    public function destroy(Request $request)
    {
        return Response::json(['msg'=> 'Pincode Delete Successfully']);
    }
}

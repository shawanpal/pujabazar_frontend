<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Customer;
use App\Pincode;

class CheckoutController extends Controller {

    protected function checkoutValidator(array $data) {
        return Validator::make($data, [
                    'name' => ['required'],
                    'email' => ['required', 'email'],
                    'flat_house_office_no' => ['required'],
                    'street_society_office_name' => ['required'],
                    'location' => ['required'],
                    'country_name' => ['required'],
                    'state' => ['required'],
                    'pin' => ['required', 'numeric', 'min:6'],
                    'address_type' => ['required'],
                    'deli_time' => ['required'],
                    'pay_method' => ['required']
        ]);
    }

    protected function createCustomer(array $data) {
        $user = Auth::user();
        return Customer::create([
                    'user_id' => $user->id,
                    'country' => $data['country_name'],
                    'state' => $data['state'],
                    'pin' => $data['pin'],
                    'location' => $data['location'],
                    'flat_house_office_no' => $data['flat_house_office_no'],
                    'street_society_office_name' => $data['street_society_office_name'],
                    'address_type' => $data['address_type'],
                    'address_other' => $data['address_other'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    protected function updateCustomer(array $data) {
        $user = Auth::user();
        return Customer::where('user_id', $user->id)->update([
                    'country' => $data['country_name'],
                    'state' => $data['state'],
                    'pin' => $data['pin'],
                    'location' => $data['location'],
                    'flat_house_office_no' => $data['flat_house_office_no'],
                    'street_society_office_name' => $data['street_society_office_name'],
                    'address_type' => $data['address_type'],
                    'address_other' => $data['address_other'],
                    'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function checkout(Request $request) {
        $user = Auth::user();
        $validator = $this->checkoutValidator($request->input());
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator);
        } else {
            $checkPin = Pincode::where(['pincode' => $request->input('pin'), 'state' => strtoupper($request->input('state')), 'status' => '1'])
                                    ->get();
            if (count($checkPin) != 0) {
                $checkCustomer = Customer::where('user_id', $user->id)
                                        ->get();
                if (count($checkCustomer) != 0) {
                    $this->updateCustomer($request->input());
                    echo 'payment pending';
                    die();
                } else {
                    $this->createCustomer($request->input());
                    echo 'payment pending';
                    die();
                }
            } else {
                return Redirect::back()
                                ->with('error', 'Sorry We are not sell this item to "' . $request->input('pin') . '" post code');
            }
        }
    }

}

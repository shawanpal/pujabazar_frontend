<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Auth;

use App\Order;

use App\Item;

use App\Category;

use App\SubCategory;

use App\Product;

use App\Package;

use App\Booking;

use App\Image;

use App\State;

use App\User;

use App\Customer;

use App\Pincode;

use Cart;

use Session;

class BuyerController extends Controller
{

    private $state,$states,$location,$allCategorys,$allSubCategorys;
    public function __construct()
    {
        $this->middleware('auth');

        // if(!Session::has('location')){
        //     $ip = \Request::ip();
        //     if($ip == '127.0.0.1'){
        //       $ip = '202.78.236.1';
        //     }
        //     $this->location = json_decode(file_get_contents("https://www.iplocate.io/api/lookup/".$ip));
        //     $state = State::where('name', $this->location->subdivision)->get();
        //     if(count($state)==0){
        //         $this->location = json_decode(file_get_contents("https://www.iplocate.io/api/lookup/202.78.236.1"));
        //     }
        //     session(['location' => $this->location->subdivision]);
        // }
        session(['location' => 'West Bengal']);

        $this->states = State::orderBy('name', 'asc')->get();
        $this->allCategorys = Category::orderBy('position', 'asc')->get();
        $this->allSubCategorys = SubCategory::join('categories', 'categories.id', '=', 'sub_categories.category_id')->leftJoin('images', 'sub_categories.id',   '=', 'images.sub_category_id')->select('sub_categories.id', 'sub_categories.category_id', 'categories.category_name', 'categories.category_url', 'sub_categories.sub_category_name', 'sub_categories.sub_category_url', 'images.image')->orderBy('sub_categories.id', 'desc')->get();
    }

    public function index()
    {
    	$cart = Cart::content();

        if(count($cart)<1){
            return redirect('buyer/order');
        }

        $user = Auth::user();
        $role = $user->role;
        $name = $user->name;
        $email = $user->email;
        $phone = $user->phone;
        $verify_phone = $user->verify_phone;

        // $day = date('D', strtotime("+3 day", strtotime(date('Y-m-d'))));
        $date = date('Y-m-d', strtotime("+3 day", strtotime(date('Y-m-d'))));
        // if($day == 'Sat'){
        //     $day = date('D', strtotime("+5 day", strtotime(date('Y-m-d'))));
        //     $date = date('Y-m-d', strtotime("+5 day", strtotime(date('Y-m-d'))));
        // }else if($day == 'Sun'){
        //     $day = date('D', strtotime("+4 day", strtotime(date('Y-m-d'))));
        //     $date = date('Y-m-d', strtotime("+4 day", strtotime(date('Y-m-d'))));
        // }
        // $day = strtoupper($day);

        $pin = '';
        $loca = '';
        $office_no = '';
        $office_nam = '';
        $add_type = '';
        $other_address = '';
        $customers = Customer::where('user_id', $user->id)->get();
        if(count($customers)>0){
            foreach ($customers as $customer) {
                $pin = $customer->pin;
                $loca = $customer->location;
                $office_no = $customer->flat_house_office_no;
                $office_nam = $customer->street_society_office_name;
                $add_type = $customer->address_type;
                $other_address = $customer->address_other;
            }

        }

        return view('checkout', array('cart' => $cart, 'role' => $role, 'name' => $name, 'email' => $email, 'phone' => $phone, 'verify_phone' => $verify_phone, 'pin' => $pin, 'location' => $loca, 'office_no' => $office_no, 'office_nam' => $office_nam, 'add_type' => $add_type, 'other_address' => $other_address, 'date' => $date));
    }

    public function myOrder(Request $request)
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        $ord = '';

        foreach ($orders as $order) {
            $price = 0;
            if($order->payment_status == 'Pending' && $order->shipping_status == 'Pending'){
                $btn = '<button type="button" value="'.$order->id.'" class="btn btn-link delete_order"><i class="fa fa-trash-o"></i></button>';
            }else{
               $btn = '<button type="button" class="btn btn-link" disabled><i class="fa fa-trash-o"></i></button>';
            }
            foreach ($order->cart as $cart) {
                $price += ($cart['price']*$cart['qty']);
            }
            $ord .= '<tr>
                        <td><button type="button" value="'.$order->id.'" class="btn btn-link show_invoice">'.$order->invoice_id.'</button></td>
                        <td>'.$order->create_at.'</td>
                        <td>'.$order->payment_id.'</td>
                        <td>'.$order->payment_status.'</td>
                        <td>'.$order->shipping_status.'</td>
                        <td><i class="fa fa-rupee"></i> '.$price.'</td>
                        <td>'.$btn.'</td>

                        </tr>';
        }

        return view('buyer/order', ['orders' => $ord]);
    }

    /*public function showInvoice(Request $request)
    {
        $user = Auth::user();
        $customers = Customer::where('user_id', $user->id)->get();
        foreach ($customers as $customer) {
            $location = $customer->location;
        }
        $orders = Order::where('id', $request->id)->get();
        $invoice = '';

        foreach ($orders as $order) {
            $invoice_id = $order->invoice_id;
            $created = date('F j, Y', strtotime($order->create_at));
            $dat = explode('~',$order->delivery_time);
            $delivery_date = date('F j, Y', strtotime($dat[0])).' - '.$dat[1];
            if($order->payment_id == ''){
                $payment_type = 'Cash on Delivery';
            }else if (substr($order->payment_id, 0, 3) === 'EC-'){
                $payment_type = 'Paypal';
            }else if (substr($order->payment_id, 0, 3) === 'MOJ'){
                $payment_type = 'Card';
            }
            $payment_status = $order->payment_status;
            $shipping_status = $order->shipping_status;
            $inv = '';
            $url = '';
            $str = '';
            $price = 0;
            foreach ($order->cart as $cart) {
                $price += ($cart['price']*$cart['qty']);
                $inv .= '<tr class="item">
                        <td>
                            '.$cart['name'];

                $url = explode('/',$cart['options']['url']);
                $str = $url[count($url)-2];
                if($str == 'package'){
                    $inv .= '<table>';
                    foreach ($order->package_item as $key => $each_array) {
                        if($each_array['package_id']==$cart['id']){

                            foreach ($each_array['items'] as $k => $v) {
                                $items = Item::where('id', $v['item_id'])->get();
                                foreach ($items as $itm) {
                                    $inv .= '<tr>
                                        <td>
                                            '.$itm->name.'
                                        </td>
                                        <td>
                                            '.($itm->size_weight*$v['quantity']).$itm->sw_unit.'
                                        </td>
                                        <td>
                                            '.($itm->quantity*$v['quantity']).$itm->q_unit.'
                                        </td>
                                    </tr>';
                                }
                            }
                        }
                    }
                    $inv .= '</table>';
                }

                $inv .= '</td>';
                $inv .= '<td>
                        '.$cart['qty'].'
                    </td>
                    <td>
                        <i class="fa fa-rupee"></i> '.($cart['price']*$cart['qty']).'
                    </td>
                </tr>';
            }
        }
       $invoice = '<div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="3">
                            <table>
                                <tr>
                                    <td class="title">
                                        <img src="'.asset('images/invoice-logo.png').'" style="max-width:150px;">
                                    </td>
                                    <td></td>
                                    <td>
                                        Invoice #: '.$invoice_id.'<br>
                                        Created: '.$created.'<br>
                                        Delivery: '.$delivery_date.'
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr class="information">
                        <td colspan="3">
                            <table>
                                <tr>
                                    <td>
                                      Puja Bazar<br>
                                      56 Purbachal Main Road<br>
                                      Kolkata-78
                                    </td>
                                    <td></td>
                                    <td>
                                        '.$user->name.'<br>
                                        '.$location.'<br>
                                        '.$user->phone.'<br>
                                        '.$user->email.'
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr class="heading">
                        <td>
                            Payment Method
                        </td>

                        <td>
                            Payment Status
                        </td>

                        <td>
                            Delivery Status
                        </td>
                    </tr>

                    <tr class="details">
                        <td>
                            '.$payment_type.'
                        </td>

                        <td>
                            '.$payment_status.'
                        </td>

                        <td>
                            '.$shipping_status.'
                        </td>
                    </tr>

                    <tr class="heading">
                        <td>
                            Item
                        </td>
                        <td>
                            Quantity
                        </td>
                        <td>
                            Price
                        </td>
                    </tr>
                    '.$inv.'
                    <tr class="total">
                        <td></td>
                        <td></td>
                        <td>
                           Total: <i class="fa fa-rupee"></i> '.number_format($price, 2).'
                        </td>
                    </tr>
                </table>
            </div>';
        return Response::json(['invoice'=>$invoice]);
    }*/
    public function showInvoice(Request $request)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        $location = $customer->location;
        $orders = Order::where('id', $request->id)->get();
        $invoice = '';

        foreach ($orders as $order) {
            $invoice_id = $order->invoice_id;
            $created = date('F j, Y', strtotime($order->create_at));
            $dat = explode('~',$order->delivery_time);
            $delivery_date = date('F j, Y', strtotime($dat[0])).' - '.$dat[1];
            if($order->payment_id == ''){
                $payment_type = 'Cash on Delivery';
            }else if (substr($order->payment_id, 0, 3) === 'EC-'){
                $payment_type = 'Paypal';
            }else if (substr($order->payment_id, 0, 3) === 'MOJ'){
                $payment_type = 'Card';
            }
            $payment_status = $order->payment_status;
            $shipping_status = $order->shipping_status;
            $inv = '';
            $url = '';
            $str = '';
            $price = 0;
            foreach ($order->cart as $cart) {
                $price += ($cart['price']*$cart['qty']);
                $inv .= '<tr class="item">
                        <td>
                            '.$cart['name'];

                $url = explode('/',$cart['options']['url']);
                $str = $url[count($url)-2];
                if($str == 'package'){
                    $inv .= '<table>';
                    foreach ($order->package_item as $key => $each_array) {
                        if($each_array['package_id']==$cart['id']){

                            foreach ($each_array['items'] as $k => $v) {
                                $items = Item::where('id', $v['item_id'])->get();
                                foreach ($items as $itm) {
                                    $inv .= '<tr>
                                        <td>
                                            '.$itm->name.'
                                        </td>
                                        <td>
                                            '.($itm->size_weight*$v['quantity']).$itm->sw_unit.'
                                        </td>
                                        <td>
                                            '.($itm->quantity*$v['quantity']).$itm->q_unit.'
                                        </td>
                                    </tr>';
                                }
                            }
                        }
                    }
                    $inv .= '</table>';
                }

                $inv .= '</td>';
                $inv .= '<td>
                        '.$cart['qty'].'
                    </td>
                    <td>
                        <i class="fa fa-rupee"></i> '.($cart['price']*$cart['qty']).'
                    </td>
                </tr>';
            }
        }

         $invoice = '<div class="contain">
             <div class="inv-title">
             <img src="'.asset('images/invoice-logo.png').'" style="max-width:200px;">
             </div>
             <div class="inv-header">
                 <div>
                     <h3>From - Puja Bazar</h3>
                     <ul>
                         <li>56 Purbachal Main Road</li>
                         <li>Kolkata-78</li>
                         <li>033 46020348 | niladripritu@gmail.com</li>
                     </ul>
                     <h3>For - '.$user->name.'</h3>
                     <ul>
                         <li>'.$location.' -'.$customer->pin.'</li>
                         <li><b>Type:</b> '.$customer->address_type.'</li>
                         <li><b>Flat/House/Office No:</b> '.$customer->flat_house_office_no.'</li>
                         <li><b>Srteet/Society/Office Name:</b> '.$customer->street_society_office_name.'</li>
                         <li><b>Other:</b> '.$customer->address_other.'</li>
                         <li>'.$user->phone.' | '.$user->email.'</li>
                     </ul>
                 </div>
                 <div>
                     <table>
                         <tr>
                             <th>Invoice #</th>
                             <td><b>'.$order->invoice_id.'</b></td>
                         </tr>
                         <tr>
                             <th>Issue Date</th>
                             <td>'.$created.'</td>
                         </tr>
                         <tr>
                             <th>Delivery Date</th>
                             <td>'.$delivery_date.'</td>
                         </tr>
                         <tr>
                             <th>Payment Method</th>
                             <td>'.$payment_type.'</td>
                         </tr>
                         <tr>
                             <th>Payment Status</th>
                             <td>'.$order->payment_status.'</td>
                         </tr>
                         <tr>
                             <th>Delivery Status</th>
                             <td>'.$order->shipping_status.'</td>
                         </tr>
                     </table>
                 </div>
             </div>
             <div class="inv-body">
                 <table>
                     <thead>
                         <th>Product</th>
                         <th>Quantity</th>
                         <th>Price</th>
                     </thead>
                     <tbody>
                       '.$inv.'
                     </tbody>
                 </table>
             </div>
             <div class="inv-footer">
                 <table>
                     <tr>
                         <th>Grand total</th>
                         <td><b>&#8377; '.number_format($price, 2).'</b></td>
                     </tr>
                 </table>
             </div>
         </div>';
        return Response::json(['invoice'=>$invoice]);
    }

    public function phoneVerification(Request $request)
    {
        $posible_text='ASDFGHJKLZXCVBNMQWERTYUP23456789';
        $code='';
        $p=0;
        while($p<4){
            $code .= substr($posible_text,mt_rand(0,strlen($posible_text)-1),1);
            $p++;
        }
        session(['sms_code' => $code]);

        $url="http://nimbusit.co.in/api/swsend.asp";
        $data="username=t1Etupa&password=17120268&sender=NewReg&sendto=".$request->numb."&message=".$code;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        $result = curl_exec($ch);
        curl_close($ch);
        return Response::json(['result' => $result]);









        /*$username = "sampayan.litongroupinc@gmail.com";
        $hash = "f60ca9fc5a230b1012a4898313538446d997840c6dc35141737cbd112af71553";

        $username = "sampayan.litongroupinc@gmail.com";
        $hash = "f60ca9fc5a230b1012a4898313538446d997840c6dc35141737cbd112af71553";

        // Config variables. Consult http://api.textlocal.in/docs for more info.
        $test = "1";

        $sender = "TXTLCL"; // This is who the message appears to be from.
        $numbers = '91'.$request->numb; // A single number or a comma-seperated list of numbers
        $message = $code;
        // 612 chars or less
        // A single number or a comma-seperated list of numbers
        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        // Process your response here
        $responseArray = json_decode($result, true);
        $status = $responseArray['status'];

        //dd($responseArray);
        if($status!='success'){
            return Response::json(['result' => $result, 'status' => $status, 'error' => $responseArray['warnings'][0]['message']]);
        }else{
          return Response::json(['status' => $status, 'code' => $code]);
        }*/
    }

    public function stopValidation(Request $request)
    {
        Session::forget('sms_code');
        return Response::json(['alert' => 'Resend the code again']);
    }

    public function varifyCode(Request $request)
    {
        if(strcmp($request->code, session('sms_code')) == 0){
             $user = Auth::user();
             User::where('id', $user->id)->update(['phone' => $request->phone, 'verify_phone' => '1']);
             $cus = Customer::where('user_id', $user->id)->get();
            if(count($cus)!=0){
                return Response::json(['status' => 'success', 'delivery_address' => true]);
            }else{
                return Response::json(['status' => 'success', 'delivery_address' => false]);
            }
        }else{
            return Response::json(['status' => 'error', 'alert' => 'Sorry Code is not match']);
        }
    }

    public function addCustomer(Request $request)
    {
        $user = Auth::user();
        $cus = Customer::where('user_id', $user->id)->get();
        if(count($cus)!=0){
            $customer = Customer::where('user_id', $user->id)->update([
                'pin' => $request->buyer_pin,
                'location' => $request->buyer_area,
                'flat_house_office_no' => $request->flt_hus_ofc_no,
                'street_society_office_name' => $request->str_soc_ofc_nam,
                'address_type' => $request->buyer_address_type,
                'address_other' => $request->buyer_address_other,
            ]);
        }else{
            $customer = Customer::create([
                'user_id' => $user->id,
                'pin' => $request->buyer_pin,
                'location' => $request->buyer_area,
                'flat_house_office_no' => $request->flt_hus_ofc_no,
                'street_society_office_name' => $request->str_soc_ofc_nam,
                'address_type' => $request->buyer_address_type,
                'address_other' => $request->buyer_address_other,
            ]);
        }
        $my_stat = strtoupper(session('location'));
        $pin_code = Pincode::where('pincode', $request->buyer_pin)->where('state', $my_stat)->where('status', '1')->get();
        //$pin_code = Pincode::orderBy('id', 'desc')->get();
        //dd($pin_code);
        if(count($pin_code)!=0){
            return Response::json(['status' => 'success']);
        }else{
           return Response::json(['status' => 'error', 'msg' => 'Sorry We are not sell this item to "'.$request->buyer_pin.'" post code']);
        }
    }

    public function deliveryTime(Request $request)
    {
        if(Session::has('delivery_time')){
            Session::forget('delivery_date');
            Session::forget('delivery_time');
        }
        session(['delivery_date' => $request->date]);
        session(['delivery_time' => $request->time]);
        //return Response::json(['msg' => '']);
    }
    public function destroy(Request $request)
    {
        $odr = Order::where('id', $request->id)->where('payment_status', 'Pending')->where('shipping_status', 'Pending')->get();
        if(count($odr)>0){

            $url = '';
            $str = '';
            $ord = '';
            $odr = Order::where('id', $request->id)->get();
            foreach ($odr as $od) {
                foreach ($od->cart as $cart) {
                    $url = explode('/',$cart['options']['url']);
                    $str = $url[count($url)-2];
                    if($str == 'package'){
                        Package::where('id', $cart['id'])->increment('stock', $cart['qty']);
                    }else if($str == 'product'){
                        Product::where('id', $cart['id'])->increment('stock', $cart['qty']);
                    }
                }
            }
            Order::where('id', $request->id)->delete();
            $orders = Order::orderBy('id', 'desc')->get();
            foreach ($orders as $order) {
                $price = 0;
                foreach ($order->cart as $cart) {
                    $price += ($cart['price']*$cart['qty']);
                }
                $ord .= '<tr>
                            <td><button type="button" value="'.$order->id.'" class="btn btn-link show_invoice">'.$order->invoice_id.'</button></td>
                            <td>'.$order->create_at.'</td>
                            <td>'.$order->payment_id.'</td>
                            <td>
                                <select id="pay_stat'.$order->id.'">';
                                if($order->payment_status=='Completed'){
                                    $ord .= '<option value="Completed" selected>Completed</option>
                                    <option value="Pending">Pending</option>';
                                }else if($order->payment_status=='Pending'){
                                    $ord .= '<option value="Completed">Completed</option>
                                    <option value="Pending" selected>Pending</option>';
                                }
                            $ord .= '</select>
                            </td>
                            <td>
                                <select id="ship_stat'.$order->id.'">';
                                if($order->shipping_status=='Completed'){
                                    $ord .= '<option value="Completed" selected>Completed</option>
                                    <option value="Shipping">Shipping</option>
                                    <option value="Pending">Pending</option>';
                                }else if($order->shipping_status=='Shipping'){
                                    $ord .= '<option value="Completed">Completed</option>
                                    <option value="Shipping" selected>Shipping</option>
                                    <option value="Pending">Pending</option>';
                                }else if($order->shipping_status=='Pending'){
                                    $ord .= '<option value="Completed">Completed</option>
                                    <option value="Shipping">Shipping</option>
                                    <option value="Pending" selected>Pending</option>';
                                }

                            $ord .= '</select>
                            </td>
                            <td><i class="fa fa-rupee"></i> '.$price.'</td>
                            <td>
                                <button type="button" value="'.$order->id.'" class="btn btn-link delete_order"><i class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>';
            }
            return Response::json(['status'=>'success', 'orders'=>$ord]);
        }else{
            return Response::json(['status'=>'error', 'error'=>'This order can\'t cancel']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Auth;

use App\User;

use App\Order;

use App\Customer;

use App\Item;

use App\Level;

use App\Seller;

use Illuminate\Support\Facades\Validator;

use App\Product;

use App\Package;

use App\State;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin()
    {
        return view('admin');
    }

    public function seller()
    {
        return view('seller');
    }

    public function allOrder()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        $ord = '';

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
        return view('admin/order', ['orders' => $ord]);
    }
    
    public function showInvoice(Request $request)
    {
        $invoice = '';
        $order = Order::where('id', $request->id)->first();
        $user = User::where('id', $order->user_id)->first();
        $customer = Customer::where('user_id', $order->user_id)->first();
        $location = $customer->location;
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

         $invoice = '<div class="contain">
             <div class="inv-title">
             <img src="'.asset('images/invoice-logo.png').'" style="max-width:200px;">
             </div>
             <div class="inv-header">
                 <div>
                     <b>From - Puja Bazar</b>
                     <ul>
                         <li>56 Purbachal Main Road</li>
                         <li>Kolkata-78</li>
                         <li>033 46020348 | niladripritu@gmail.com</li>
                     </ul>
                     <b>For - '.$user->name.'</b>
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

    public function shippingStatus(Request $request)
    {
        if($request->shipping_status==''){
            $orders = Order::where('id', $request->id)->get();
            foreach ($orders as $order) {
                $shipping_status = $order->shipping_status;
            }
            return Response::json(['status' => $shipping_status]);
        }else{
           Order::where('id', $request->id)->update(['shipping_status' => $request->shipping_status]);
            $orders = Order::where('id', $request->id)->get();
            foreach ($orders as $order) {
                $invoice_id = $order->invoice_id;
            }
            return Response::json(['status' => 'Success', 'msg'=> 'Shipping status successfully change for this invoice- '.$invoice_id]);
        }
    }

    public function paymentStatus(Request $request)
    {
        /*$orders = Order::where('id', $request->id)->get();
        foreach ($orders as $order) {
            if($order->payment_status == 'Completed'){
                $invoice_id = $order->invoice_id;
                return Response::json(['status' => 'paid', 'msg'=> 'This invoice ('.$invoice_id.') already paid']);
            }
        }*/
        Order::where('id', $request->id)->update(['payment_status' => $request->payment_status]);
        $orders = Order::where('id', $request->id)->get();
        foreach ($orders as $order) {
            $invoice_id = $order->invoice_id;
        }
        return Response::json(['status' => 'Success', 'msg'=> 'Shipping status successfully change for this invoice- '.$invoice_id]);
    }

    public function commition(Request $request)
    {
        $sellers = Seller::all();
        return view('admin/commition', ['sellers' => $sellers]);
    }

    public function calculateCommition(Request $request)
    {
        $rules = array(
            'seller' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        );
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{

            $commition = '';
            $total = 0;
            $selrs = Seller::join('levels', 'sellers.level_id', '=', 'levels.id')->select('sellers.name', 'levels.commission')->where('sellers.id', $request->seller)->get();
            foreach ($selrs as $selr) {
                $seller_name = $selr->name;
                $seller_com = $selr->commission;
            }
            $orders = Order::where('payment_status', 'Completed')->get();
            foreach ($orders as $order) {
                $created = date('Y-m-d', strtotime($order->create_at));
                if($created>=$request->start_date && $created<=$request->end_date){
                    foreach ($order->cart as $cart) {
                        $url = $cart['options']['url'];
                        $url = explode('/',$url);
                        $type = $url[count($url)-2];
                        if($type == 'product'){
                            $query = Product::where('id', $cart['id'])->where('seller_id', $request->seller)->get();
                            if(count($query)>0){
                                $total += (($seller_com/100)*($cart['price']*$cart['qty']));
                            }
                        }else if($type == 'package'){
                            $query = Package::where('id', $cart['id'])->where('seller_id', $request->seller)->get();
                            if(count($query)>0){
                                $total += (($seller_com/100)*($cart['price']*$cart['qty']));
                            }
                        }

                        //$commition .= $type.'//'.$cart['qty'].'//'.($cart['price']*$cart['qty']).'//'.$seller_com.'//'.(($seller_com/100)*($cart['price']*$cart['qty'])).'//'.$created.'//'.$request->start_date.'<br>';
                        //$price += ($cart['price']*$cart['qty']);
                    }
                }

            }
            $commition = '<div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>'.$seller_name.'</strong>  Total Commition is <strong> <i class="fa fa-rupee"></i> '.$total.'</strong>
                </div>';
            return Response::json(['commition' => $commition]);
        }
    }

    public function destroy(Request $request)
    {
        $odr = Order::where('id', $request->id)->where('payment_status', 'Completed')->get();
        if(count($odr)>0){
            return Response::json(['status'=>'error', 'error'=>'This order payment already pay']);
        }else{
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
        }
    }
}

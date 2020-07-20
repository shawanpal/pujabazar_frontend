<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;

use App\Customer;

use App\Item;

use App\Order;

use App\Product;

use App\Package;

use Cart;

use View;

use Session;

use Redirect;

class PaymentController extends Controller
{
		public function cashOnDelivery(Request $request){
        $date = date('c');
        $invoice_id = uniqid();
        $user = Auth::user();
				$customer = Customer::where('user_id', $user->id)->first();
        $package_item = null;
        if(Session::has('order')){
            $package_item = Session('order');
            Session::forget('order');
        }
        $ord = Order::create([
            'user_id' => $user->id,
            'invoice_id' => $invoice_id,
            'cart' => Cart::content(),
            'package_item' => $package_item,
            'create_at' => $date,
            'delivery_time' => session('delivery_date').'~'.session('delivery_time'),
						'payment_status' => 'Pending',
						'shipping_status' => 'Pending',
        ]);
        if($ord){
            Session::forget('delivery_time');
						Session::forget('delivery_date');
						Session::save();

						Mail::to($user)->send(new OrderShipped($ord, $user, $customer));

            if( count(Mail::failures()) > 0 ) {
                return Response::json(['status' => 'error', 'msg' => 'Email Not send!']);
            }else {
								Cart::destroy();
                Session::put('success','Your order is confirmed & Order details sent to your email, Enjoy!!');
                return Response::json(['status' => 'success', 'url' => route('home')]);
            }

        }else{
            return Response::json(['status' => 'error', 'msg' => 'You have some error!']);
        }


        //return redirect()->route('home');
    }

    public function payWithCard(Request $request)
    {
        $rules = array(
            'buyer_name' => 'required|string',
            'buyer_phone' => 'required|numeric',
            'buyer_email' => 'required|string'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $invoice_id = uniqid();
            if(Session::has('invoice_id')){
                Session::forget('invoice_id');
            }
            session(['invoice_id' => $invoice_id]);

            $ch = curl_init();

            // For Live Payment
            // curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/');
            // For Test payment
            curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array("X-Api-Key:test_3501d7a3a649d2f88d4389b868b",
                    "X-Auth-Token:test_c209027897f471dac9f0f052b0b"));
            $payload = Array(
                'purpose' => "Order #{$invoice_id} Invoice",
                'amount' => Cart::total(),
                'phone' => $request->buyer_phone,
                'buyer_name' => $request->buyer_name,
                'redirect_url' => route('returnurl'),
                'send_email' => true,
                'webhook' => 'http://instamojo.com/webhook/',
                'send_sms' => true,
                'email' => $request->buyer_email,
                'allow_repeated_payments' => false
            );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));

            $response = curl_exec($ch);

            $err = curl_error($ch);
            curl_close($ch);
            if ($err) {
                //\Session::put('error','Payment Failed, Try Again!!');
                return Response::json(['error' => 'Payment Failed, Try Again!!']);
                //return redirect()->back();
            } else {
                $data = json_decode($response);
                return Response::json(['result' => $data, 'url' => $data->payment_request->longurl]);
            }


            /*if($data->success == true) {
                return redirect($data->payment_request->longurl);
            } else {
                \Session::put('error','Payment Failed, Try Again!!');
                return redirect()->back();
            }*/
        }
    }

    public function returnurl(Request $request)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payments/'.$request->get('payment_id'));
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:test_3501d7a3a649d2f88d4389b868b",
                "X-Auth-Token:test_c209027897f471dac9f0f052b0b"));

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            \Session::put('error','Payment Failed, Try Again!!');
            return redirect()->route('checkout');
        } else {
            $data = json_decode($response);
        }

        if($data->success == true) {
            if($data->payment->status == 'Credit') {
                $user = Auth::user();
								$customer = Customer::where('user_id', $user->id)->first();
                $package_item = null;
                if(Session::has('order')){
                    $package_item = Session('order');
                    Session::forget('order');
                }
                $ord = Order::create([
                    'user_id' => $user->id,
                    'invoice_id' => session('invoice_id'),
                    'payment_id' => $data->payment->payment_id,
                    'cart' => Cart::content(),
                    'package_item' => $package_item,
                    'create_at' => $data->payment->created_at,
                    'delivery_time' => session('delivery_date').'~'.session('delivery_time'),
                    'payment_status' => 'Completed'
                ]);

                if($ord){
									Session::forget('delivery_time');
									Session::forget('delivery_date');
									Session::save();

										Mail::to($user)->send(new OrderShipped($ord, $user, $customer));

                    if( count(Mail::failures()) > 0 ) {
                        Session::put('success','Your payment has been pay successfully & Your Order details will send you soon to your email, Enjoy!!');
                        return redirect()->route('home');
                    }else {
												Cart::destroy();
                        Session::put('success','Your payment has been pay successfully & Your Order details send to your email, Enjoy!!');
                        return redirect()->route('home');
                    }
                }

            }else{
                Session::put('error','Payment Failed, Try Again!!');
                return redirect()->route('checkout');
            }
        } else {
            \Session::put('error','Payment Failed, Try Again!!');
            return redirect()->route('checkout');
        }
    }

    public function store(Request $request)
    {
    	$provider = new ExpressCheckout;
    	$token = $request->token;
    	$PayerID = $request->PayerID;

    	$response = $provider->getExpressCheckoutDetails($token);

    	$invoiceId = $response['INVNUM'];

    	$data = $this->cartDate($invoiceId);

			$response = $provider->doExpressCheckoutPayment($data, $token, $PayerID);

        $response = $provider->getExpressCheckoutDetails($token);

        if($response['CHECKOUTSTATUS'] == 'PaymentActionCompleted'){
            $user = Auth::user();
						$customer = Customer::where('user_id', $user->id)->first();
            $package_item = null;
            if(Session::has('order')){
                $package_item = Session('order');
                Session::forget('order');
            }
            $ord = Order::create([
                'user_id' => $user->id,
                'invoice_id' => $invoiceId,
                'payment_id' => $response['TOKEN'],
                'cart' => Cart::content(),
                'package_item' => $package_item,
                'create_at' => $response['TIMESTAMP'],
                'delivery_time' => session('delivery_date').'~'.session('delivery_time'),
                'payment_status' => 'Completed'
            ]);
            if($ord){

								Session::forget('delivery_time');
								Session::forget('delivery_date');
								Session::save();

								Mail::to($user)->send(new OrderShipped($ord, $user, $customer));
                if( count(Mail::failures()) > 0 ) {
                    Session::put('success','Your payment has been pay successfully & Your Order details will send you soon to your email, Enjoy!!');
                    return redirect()->route('home');
                }else {
									Cart::destroy();
                    Session::put('success','Your payment has been pay successfully & Your Order details send to your email, Enjoy!!');
                    return redirect()->route('home');
                }
            }

        }else{
            \Session::put('error','Payment Failed, Try Again!!');
            return redirect()->route('checkout');
        }

		//Create the Order
    }

		public function payWithPaypal(Request $request)
	  {
	    	$provider = new ExpressCheckout; // To use express checkout.

	    	$invoiceId = uniqid();
	    	$data = $this->cartDate($invoiceId);
	    	$options = [
			    'SOLUTIONTYPE' => 'Sole',
				];
	        $response = $provider->addOptions($options)->setExpressCheckout($data);

			// This will redirect user to PayPal
			return redirect($response['paypal_link']);
		}

		protected function cartDate($invoiceId)
		{
			$data = [];
			$data['items'] = [];
			foreach(Cart::content() as $key => $cart){
				$itemDetail=[
					'name' => $cart->name,
			        'price' => $cart->price,
			        'qty' => $cart->qty
				];
				$data['items'][] = $itemDetail;
			}

			$data['invoice_id'] = $invoiceId;
			$data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
			$data['return_url'] = route('payment.store');
			$data['cancel_url'] = route('cart');
			$data['total'] = Cart::total();
			return $data;
		}
}

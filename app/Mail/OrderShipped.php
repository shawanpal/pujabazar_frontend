<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Order;
use App\Product;
use App\Package;
use App\Item;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;
    public $user;
    public $customer;
    public $location;
    public $items;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $user, $customer)
    {
        $this->order = $order;
        $this->user = $user;
        $this->customer = $customer;
        $this->location = $customer->location;
        $this->invoice_id = $order->invoice_id;

            foreach ($order->cart as $cart) {
                $url = explode('/',$cart['options']['url']);
                $str = $url[count($url)-2];
                if($str == 'package'){
                    Package::where('id', $cart['id'])->decrement('stock', $cart['qty']);
                    foreach ($order->package_item as $key => $each_array) {
                        if($each_array['package_id']==$cart['id']){
                            foreach ($each_array['items'] as $k => $v) {
                                $this->items[$cart['id']][$k] = Item::where('id', $v['item_id'])->get();
                            }
                        }
                    }
                }else if($str == 'product'){
                    Product::where('id', $cart['id'])->decrement('stock', $cart['qty']);
                }
            }
            // dd($this->customer->pin);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order confirmed '.$this->invoice_id)->view('email.order');
    }
}

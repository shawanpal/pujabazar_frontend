<?php
  $grand_price = 0;
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
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Invoice - #{{ $order->invoice_id }}</title>
        <style type="text/css">
            @page {
                margin: 0px;
            }
            body {
                margin: 0px;
            }
            * {
                font-family: Verdana, Arial, sans-serif;
            }
            a {
                color: #fff;
                text-decoration: none;
            }
            table {
                font-size: x-small;
            }
            tfoot tr td {
                font-weight: bold;
                font-size: x-small;
            }
            .invoice table {
                /* margin: 15px; */
                padding: 0px 15px;
            }
            .invoice h3 {
                margin-left: 15px;
            }
            .information {
                background-color: #60a7a6;
                color: #fff;
                width: 100%;
            }
            .information .logo {
                margin: 5px;
            }
            .information table {
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="information">
            <table width="100%">
                <tr>
                    <td align="left" style="width: 40%;">
                        <h3>{{ $user->name }}</h3>
                        <pre>
{{ $location }} -{{ $customer->pin }}
<b>Type:</b> {{ $customer->address_type }}
<b>Flat/House/Office No:</b> {{ $customer->flat_house_office_no }}
<b>Srteet/Society/Office Name:</b> {{ $customer->street_society_office_name }}
<b>Other:</b> {{ $customer->address_other }}
{{ $user->phone }} | {{ $user->email }}
<br /><br />
Invoice: #{{ $order->invoice_id }}
Date: {{ $created }}
Delivery: {{ $delivery_date }}
Payment Method: {{ $payment_type }}
Payment Status: {{ $order->payment_status }}
Delivery Status: {{ $order->shipping_status }}

</pre>
                    </td>
                    <td align="center"><img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Logo" width="200px" class="logo" /></td>
                    <td align="right" style="width: 40%;">
                        <h3>Puja Bazar</h3>
                        <pre>
                    {{config('app.url')}}

                    56 Purbachal Main Road
                    Kolkata-78
                    033 46020348 | niladripritu@gmail.com
                        </pre>
                    </td>
                </tr>
            </table>
        </div>
        <br />
        <div class="invoice">
            <h3>Invoice specification #{{ $order->invoice_id }}</h3>
            <table width="100%">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($order->cart as $cart)
                        <?php
                          $grand_price += ($cart['price']*$cart['qty']);
                          $url = explode('/',$cart['options']['url']);
                          $str = $url[count($url)-2];
                        ?>
                        <tr>
                          <td><b>{{ $cart['name'] }}</b>
                            @if($str == 'package')
                                <table>
                                @foreach ($order->package_item as $key => $each_array)
                                    @if($each_array['package_id']==$cart['id'])
                                    <?php $i = 0; ?>
                                        @foreach ($each_array['items'] as $k => $v)
                                          @foreach ($items[$cart['id']] as $itms)
                                            @foreach ($itms as $itm)
                                              @if($i == 0)
                                                <tr>
                                                    <td>{{ $itm->name }}</td>
                                                    <td>{{($itm->size_weight*$v['quantity']).$itm->sw_unit}}</td>
                                                    <td>{{($itm->quantity*$v['quantity']).$itm->q_unit}}</td>
                                                </tr>
                                              @endif
                                            @endforeach
                                          @endforeach
                                          <?php $i++; ?>
                                        @endforeach
                                    @endif
                                @endforeach
                                </table>
                            @endif
                          </td>
                          <td>{{ $cart['qty'] }}</td>
                          <td align="left">&#8377; {{ ($cart['price']*$cart['qty']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="1"></td>
                        <td align="left">Total</td>
                        <td align="left" class="gray">&#8377; {{ number_format($grand_price, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="information" style="position: absolute; bottom: 0;">
            <table width="100%">
                <tr>
                    <td align="left" style="width: 50%;">&copy;{{date('Y')}}{{config('app.url')}}- All rights reserved.</td>
                    <td align="right" style="width: 50%;">Puja Bazar</td>
                </tr>
            </table>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        @media print {
            @page{
                size: A4;
            }
        }
        ul{
            padding: 0;
            list-style: none;
            border-bottom: 1px solid silver;
        }
        body{
            font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
            margin: 0;
        }
        .container{
            padding: 5px;
            max-width: 800px;
            margin: auto;
        }
        .inv-header{
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .inv-header :nth-child(2){
            flex-basis: 50%;
        }
        .inv-title{
            padding: 5px;
            border-bottom: 1px solid silver;
            text-align: center;
            margin-bottom: 20px;
        }
        .no-margin{
            margin: 0;
        }
        .inv-logo{
            width: 150px;
        }
        .inv-header h2{
            font-size: 20px;
            margin: 1rem 0 0 0;
        }
        .inv-header ul li{
            font-size: 15px;
            padding: 3px 0;
        }

        /* table in head */
        .inv-header table{
            width: 100%;
            border-collapse: collapse;
        }
        .inv-header table th, .inv-header table td, .inv-header table{
            border: 1px solid silver;
        }
        .inv-header table th{
            text-align: left;
            padding: 8px;
        }
        .inv-header table td{
            text-align: right;
            padding: 8px;
        }

        /* Body */
        .inv-body{
            margin-bottom: 10px;
        }
        .inv-body table{
            width: 100%;
            /* border: 1px solid silver; */
            border-collapse: collapse;
        }
        .inv-body table th, .inv-body table td{
            padding: 10px;
            border: 1px solid silver;
        }
        .inv-body table td h5, .inv-body table td p{
            margin: 0 5px 0 0;
        }
        /* Footer */
        .inv-footer{
            clear: both;
            overflow: auto;
        }
        .inv-footer table{
            width: 30%;
            float: right;
            border: 1px solid silver;
            border-collapse: collapse;
        }
        .inv-footer table th, .inv-footer table td{
            padding: 8px;
            text-align: right;
            border: 1px solid silver;
        }
    </style>
</head>
<body>
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
    <div class="container">
        <div class="inv-title">
            <img src="{{ $message->embed(public_path('images/invoice-logo.png')) }}" style="max-width:200px;">
        </div>
        <div class="inv-header">
            <div>

                <h3>From - Puja Bazar</h3>
                <ul>
                    <li>56 Purbachal Main Road</li>
                    <li>Kolkata-78</li>
                    <li>033 46020348 | niladripritu@gmail.com</li>
                </ul>
                <h3>For - {{ $user->name }}</h3>
                <ul>
                    <li>{{ $location }} -{{ $customer->pin }}</li>
                    <li><b>Type:</b> {{ $customer->address_type }}</li>
                    <li><b>Flat/House/Office No:</b> {{ $customer->flat_house_office_no }}</li>
                    <li><b>Srteet/Society/Office Name:</b> {{ $customer->street_society_office_name }}</li>
                    <li><b>Other:</b> {{ $customer->address_other }}</li>
                    <li>{{ $user->phone }} | {{ $user->email }}</li>
                </ul>
            </div>
            <div>
                <table>
                  <tr>
                      <th>Invoice #</th>
                      <td><b>{{ $order->invoice_id }}</b></td>
                  </tr>
                    <tr>
                        <th>Issue Date</th>
                        <td>{{ $created }}</td>
                    </tr>
                    <tr>
                        <th>Delivery Date</th>
                        <td>{{ $delivery_date }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>{{ $payment_type }}</td>
                    </tr>
                    <tr>
                        <th>Payment Status</th>
                        <td>{{ $order->payment_status }}</td>
                    </tr>
                    <tr>
                        <th>Delivery Status</th>
                        <td>{{ $order->shipping_status }}</td>
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
                        <td><b>{{ $cart['qty'] }}</b></td>
                          <td><b>&#8377; {{ ($cart['price']*$cart['qty']) }}</b></td>
                      </tr>
                  @endforeach
                </tbody>
            </table>
        </div>
        <div class="inv-footer">
            <table>
                <tr>
                    <th>Grand total</th>
                    <td><b>&#8377; {{ number_format($grand_price, 2) }}</b></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

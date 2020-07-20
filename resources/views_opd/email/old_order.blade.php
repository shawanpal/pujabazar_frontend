<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style type="text/css">
      /*------------------------ INVOICE START ----------------------------*/
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: center;
        }
        .invoice-box table tr td:nth-child(3) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(3) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
      /*------------------------ INVOICE END ----------------------------*/
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
  <div class="invoice-box">
          <table cellpadding="0" cellspacing="0">
              <tr class="top">
                  <td colspan="3">
                      <table>
                          <tr>
                              <td class="title">
                                <img src="{{ $message->embed(public_path('images/invoice-logo.png')) }}" style="max-width:150px;">
                              </td>
                              <td></td>
                              <td>
                                  Invoice #: {{ $order->invoice_id }}<br>
                                  Created: {{ $created }}<br>
                                  Delivery: {{ $delivery_date }}
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
                              <td>{{ $user->name }}<br>{{ $user->phone }}<br>{{ $user->email }}</td>
                              <td>
                                {{ $location }}<br>{{ $customer->pin }}<br>{{ $customer->location }}<br>{{ $customer->flat_house_office_no }}<br>{{ $customer->street_society_office_name }}<br>{{ $customer->address_type }}<br>{{ $customer->address_other }}
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>

              <tr class="heading">
                  <td>Payment Method</td>
                  <td>Payment Status</td>
                  <td>Delivery Status</td>
              </tr>

              <tr class="details">
                  <td>{{ $payment_type }}</td>
                  <td>{{ $order->payment_status }}</td>
                  <td>{{ $order->shipping_status }}</td>
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
              @foreach ($order->cart as $cart)
                  <?php
                    $grand_price += ($cart['price']*$cart['qty']);
                    $url = explode('/',$cart['options']['url']);
                    $str = $url[count($url)-2];
                  ?>
                  <tr class="item">
                          <td>{{ $cart['name'] }}


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
                      <td>
                          <i class="fa fa-rupee"></i> {{ ($cart['price']*$cart['qty']) }}
                      </td>
                  </tr>
              @endforeach
              <tr class="total">
                  <td></td>
                  <td></td>
                  <td>
                     Total: <i class="fa fa-rupee"></i> {{ number_format($grand_price, 2) }}
                  </td>
              </tr>
          </table>
      </div>

</body>
</html>

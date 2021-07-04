<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- <html> -->
<head>
    <title>Invoice</title>
</head>
<body>
    <style>
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
      
      .invoice-box table tr.total td:nth-child(4) {
          border-top: 2px solid #eee;
          font-weight: bold;
      }
      .invoice-box table tr.total td:nth-child(5) {
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
    </style>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="5">
                    <table>
                        <tr>
                            <td class="title">
                              @if (isset($company_logo))
                              <img src="{{$company_logo}}" alt="">
                              </div>
                              @endif
                            </td>
                            
                            <td>
                                Invoice #: 123<br>
                                {{ $date }}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="5">
                    <table>
                        <tr>
                            <td>
                              Invoice To: {{ $customer->name }}<br>
                              Address: {{ $customer->address }}<br>
                              EMAIL: <a href="mailto:john@example.com">{{ $customer->email_details[0]['email'] }}</a><br>
                              Phone Number: {{ $customer->phone_number_details[0]['mobile_no'] }}<br>
                            </td>
                            
                            <td>
                              Total Due: {{$total_details_price}}<br>
                              Bank name: {{$bank_name}}<br>
                              Country: {{$bank_country}}<br>
                              City: {{$bank_city}}<br>
                              Address: {{$bank_address}}<br>
                              IBAN: {{$bank_iban}}<br>
                              SWIFT Code: {{$bank_swift}}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>
                    SERVICE
                </td>
                <td>
                    DESCRIPTION
                </td>
                <td>
                    PRICE
                </td>
                <td>
                    QTY
                </td>
                <td>
                    TOTAL
                </td>
            </tr>
            @foreach ($descriptions as $description)
            <tr class="item">
                <td>{{$description['title']}}</td>
                <td>{{$description['description']}}</td>
                <td>{{$description['price']}}</td>
                <td>{{$description['quantity']}}</td>
                @php
                    $total = (float)$description['price'] * (int)$description['quantity'];
                @endphp
                <td class="total">{{$total}}</td>
            @endforeach
            <tr class="last-item">
                <td></td>
                <td></td>   
                <td></td>
                <td>Sub Total: </td>             
                <td>
                  {{$total_details_price}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>   
                <td></td>
                <td>Tax: </td>             
                <td>
                  {{$tax_price}}
                </td>
            </tr>
            <tr class="total">
                <td></td>
                <td></td>   
                <td></td>
                <td>Total:  </td>             
                <td>
                  {{$total_with_tax}}
                </td>
            </tr>
        </table>
        <div id="notices">
          <div>Authorized Person:</div>
          <div class="notice"><img width="150" src="{{$signature}}"></div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice Print</title>
    <style>
        @media print {

            /* Hide the URL */
            .url {
                display: none !important;
            }

            @page {
                margin: 0;
            }
        }

        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .container {
            width: 796px;
            display: block;
            margin: auto;
            padding: 0px 30px
        }

        h1 {
            font-size: 40px;
            font-weight: 400;
        }

        .invoice h1 {
            color: #00000088
        }

        p {
            font-weight: 400;
            margin-bottom: 5px;
        }

        .contact p {
            text-align: end;
        }

        .custom-header-text-color {
            color: #00000088
        }

        .header {
            display: flex;
            justify-content: space-between;
            padding: 20px 15px;

            background: {
                    {
                    $invoiceData['invoice_color']
                }
            }

            ;
            color: #fff;
            margin-bottom: 40px;
        }

        .header .invoice img {
            max-width: 180px;
            margin-top: 0px;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .client-info {
            display: flex;
            flex-direction: row;
            padding: 0 15px;
        }

        .client-info .client-left {
            width: 50%;
        }

        .client-middel,
        .client-right {
            width: 25%;
        }

        .client-info h5,
        .terms h5 {
            font-weight: 400;
            color: #959595;
            text-transform: capitalize;
            margin-bottom: 10px;
        }

        .client-right h2 {
            font-size: 40px;
            color: #0D83DD;
            font-weight: 400;
            margin-top: 10px
        }

        .table-info {
            margin-top: 40px;
            width: 100%;
        }

        .total-info {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
        }

        .table-total-info {
            width: 40%;
        }

        .table-total-info p {
            position: relative;
            color: #0D83DD;
            text-align: end;
            padding: 0px 120px 0px 0px;
        }

        .terms {
            width: 60%;
        }

        .custom-invoice-terms p {
            color: #00000088 !important;
            font-size: 15px;
        }

        .custom-invoice-terms-bold-color {
            color: #00000088;
            font-size: 15px;
        }

        .table-total-info p span {
            position: absolute;
            right: 0px;
            color: #000;
        }

        .margin-top {
            margin-top: 30px;
        }

        .custom-text-justify {
            text-align: justify;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: center
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            text-align: left;
            text-transform: capitalize;
            color: #0D83DD;
            font-weight: 400;
            border-top: 3px solid #0D83DD;
            text-align: center;
            font-weight: 600;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6
        }

        .table .table {
            background-color: #fff
        }


        .table-bordered {
            border: 1px solid #dee2e6
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6
        }

        .table-bordered thead td,
        .table-bordered thead th {
            border-bottom-width: 2px
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05)
        }

        .text-start {
            text-align: start !important;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="invoice">
                @if(isset($invoiceData['invoice_logo']) && $invoiceData['invoice_logo'] != null)
                <img class="custom-invoice-logo"
                    src="{{ asset('backend/uploads/invoiceLogoImg/'.$invoiceData['invoice_logo']) }}"
                    class="img-fluid" />
                @else
                <h1>Invoice</h1>
                @endif
            </div>
            <div class="contact custom-header-text-color">
                {{-- <p><b>Project: </b> {{$invoiceData['project_title']}}</p>
                <p><b>Address: </b> {{$invoiceData['project_address']}}</p>
                <p><b>Phone 1: </b> {{$invoiceData['phone1']}}</p>
                @if(isset($invoiceData['email']) && $invoiceData['email'] != null)
                <p><b>Email: </b> {{$invoiceData['email']}}</p>
                @endif --}}
            </div>
        </div>
        <div class="client-info">
            <div class="client-left">
                <h5>invoice number</h5>
                <p>#{{$invoiceData['invoice']}}</p>
                <h5>date of issue</h5>
                <p>{{Carbon\Carbon::parse($invoiceData['date'])->format('d-m-Y')}}</p>
            </div>
            <div class="client-middel">
                
            </div>
            <div class="client-right">
                <h5>invoice total</h5>
                <h2>{{$invoiceData['payment_total_amount']}}৳</h2>
            </div>
        </div>
        <div class="table-info">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" scope="col"><span>Expense</span></th>
                        <th class="text-center" scope="col"><span>Details</span></th>
                        <th class="text-center" scope="col"><span>Total</span></th>
                        <th scope="col" class="text-center"> Paid </th>
                        <th scope="col" class="text-center"> Due </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoiceData['billing_service_data'] as $key=>$singleBPP)
                    @if(isset($singleBPP) && $singleBPP != null)
                    <tr>
                        <td class="text-start">
                            <div class="row_title">
                                <b>Category: </b>{{$singleBPP->expenseCategoryData->category_name}}
                                <br>
                                <b>Expense: </b>{{$singleBPP->expenseData->expense_name}}
                            </div>
                            <div class="row-actions">
                                @if (Auth::user()->can('billing-edit'))
                                @endif
                            </div>
                        </td>

                        <td class="text-start">
                            <b>Payee: </b>{{$singleBPP->payeeData->payee_name}}
                            <br>
                            <b>Details: </b>{{$singleBPP->expense_details}}
                        </td>

                        <td class="text-center">
                            <span class="fw-bolder text-success">{{$singleBPP->expense_amount}}</span>
                        </td>
                        <td class="text-center">{{$singleBPP->grand_total_paid}}</td>
                        <td class="text-center">{{$singleBPP->grand_total_due}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="total-info">
            <div class="terms custom-invoice-terms custom-text-justify">
                {{-- <h5>invoice terms</h5>
                <p class="custom-invoice-terms-bold-color"><b>{!! $invoiceData['invoice_terms'] !!}</b></p> --}}
            </div>
            <div class="table-total-info">
                <p>Expense Service Qty: <span>{{$invoiceData['sub_total_product_qty']}}</span></p>
                <p>Total Amount: <span>{{$invoiceData['sub_total_price']}}৳</span></p>
                <p class="margin-top">Paid Amount: <span>{{$invoiceData['paid_amount']}}৳</span></p>
                <p>Due Amount: <span>{{$invoiceData['due_amount']}}৳</span></p>
                <p>Change Amount: <span>{{$invoiceData['change_amount']}}৳</span></p>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $( document ).ready(function() {
                $(function () {
                    'use strict';
                    window.print();
                });
            });
    </script>
</body>

</html>
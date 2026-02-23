@extends('backend.master')
@section('title') Income Receipt | Amali @endsection
@section('get-client-due-bill-payment-invoice') active @endsection
@section('get-client-due-bill-payment-invoice') active @endsection
@section('styles')
<style>
    .custom-billing-invoice-logo img {
        height: auto !important;
        width: auto !important;
    }
</style>
@endsection


@section('main_content_section')

<div class="row min-vh-100">
    <!-- all content will be here. start -->
        <input type="hidden" name="income_receipt_id" id="income_receipt_id"
            value="{{$singleIncomeReceiptData->id}}">

        <div class="row invoice p-0 m-0">
            <div class="col-md-9 p-0 m-0">
                <div class="shadow rounded m-3">

                    @php
                    ///To get logo...
                    $softLogo = App\Models\InvoiceLogo::getSoftwareInvoiceLogo();
                    @endphp
                    <div class="d-md-flex justify-content-between custom-billing-invoice-header">
                        <div class="custom-invoice-logo-section">
                            <div class="d-flex align-items-center  custom-billing-invoice-logo">
                                @if(isset($softLogo) && $softLogo->logo_image != null)
                                <img src="{{ asset('backend/uploads/invoiceLogoImg/'.$softLogo->logo_image) }}"
                                    class="mt-4" alt="logo">
                                @else
                                <img src="{{asset('backend')}}/billing_invoice_logo.png" class="mt-4" alt="logo">
                                @endif
                            </div>
                        </div>
                        <div class="custom-issue-due-date-section">

                            <div class="d-flex align-items-center gap-2">
                                <div
                                    class="d-flex flex-column justify-content-end custom-issue-due-date-text-area gap-2">
                                    <p class="">Receipt Id: </p>
                                    <p class="">Date: </p>
                                    <p class="">Reciever: </p>
                                </div>

                                <div class="d-flex flex-column gap-2 custom-billing-invoice-header-right">
                                    <input type="text" class="form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                        value="#{{$singleIncomeReceiptData->income_receipt_id}}" readonly>
                                    <input type="text"
                                        class=" form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                        name="date_issue" id="date_issue" placeholder="DD-MM-YYYY"
                                        value="{{Carbon\Carbon::parse($singleIncomeReceiptData->created_at->toDateString())->format('d-m-Y')}}"
                                        readonly>
                                    <input type="text"
                                        class="form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                        value="{{Str::title(Auth::user()->name)}}" readonly>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>

                    {{-- //To get update income details page... --}}
                    <span>

                        <div class="row px-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <h5>Income Receipt List</h5>
                                    <table class="table table-bordered py-3" id="updateIncomeDetails">
                                        <!-- <hr class="mb-1"> -->
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col"><span>SN</span></th>
                                                <th class="text-center" scope="col"><span>Income</span></th>
                                                <th class="text-center" scope="col"><span>Details</span></th>
                                                <th class="text-center" scope="col"><span>Amount</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unpaidIncomeServiceData as $key=>$item)
                                            @if(isset($item) && $item != null)
                                            <tr class="text-center">
                                                <td>
                                                    <b>{{$key+1}}</b>
                                                </td>
                                                <td class="text-start">
                                                    <div class="row_title">
                                                        <b>Category: </b>{{$item->incomeCategoryData->category_name}}
                                                        <br>
                                                        <b>Income: </b>{{$item->incomeData->income_name}}
                                                    </div>
                                                    <div class="row-actions w-50">
                                                        @if (Auth::user()->can('billing-edit'))
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="text-start w-50">
                                                    <b>Receiver: </b>{{$item->receiverData->receiver_name}}
                                                    <br>
                                                    <b>Details: </b>{{$item->income_details}}
                                                </td>

                                                <td class="text-center">
                                                    <span
                                                        class="fw-bolder text-success">{{$item->income_amount}}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="custom-invoice-common-padding pb-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="">

                                        <p class="mb-1">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nisi, illo!
                                        </p>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <div class="w-100 d-flex gap-2">
                                        <div class="text-end w-50 custom-footer-right-text-block">
                                            <p>Income Qty:</p>
                                            <p>Total Amount:</p>
                                        </div>
                                        <div class="d-flex flex-column gap-2 w-50">
                                            <input type="text" name="sub_total_income_quantity"
                                                id="sub_total_income_quantity"
                                                class="form-control custom-cursor-pointer custom-readonly-bg-color"
                                                value="{{ $totalIncomeServiceQty > 0 ? $totalIncomeServiceQty : 0}}" readonly>
                                            <input type="text" name="sub_total_income_amount"
                                                id="sub_total_income_amount"
                                                class="form-control custom-cursor-pointer custom-readonly-bg-color"
                                                value="{{ $totalIncomeServiceAmount > 0 ? $totalIncomeServiceAmount : 0}}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </span>

                </div>
            </div>
            <div class="col-md-3 p-0 m-0">
                <div class="row p-3">
                    <div class="col-12 mb-4">
                        <div class="">
                            <div class="card card-stats">
                                <div class="card-header custom-payment-section-header">
                                    <div class="icon icon-warning d-flex">
                                        <span class="material-symbols-outlined">equalizer</span>
                                        <p
                                            class="category custom-card-header-title custom-payment-section-heading-title">
                                            <strong>Income Receipt</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-footer p-0 dashboard-card-footer">
                                    <div class="stats">
                                        <div class="row">
                                            <a href="javascript:void(0)"
                                            class="text-white btn">
                                            {{-- <a href="{{route('print-all-income-receipt', $singleIncomeReceiptData->id)}}"  --}}
                                                {{-- class="text-white btn" target="_blank"> --}}
                                                <div class="col-12 invoice-print-button text-center py-2">
                                                    <i class="material-symbols-outlined custom-bill-pay-icon">print</i>Print Receipt
                                                </div>
                                            </a>

                                            <a class="text-white btn" href="{{route('income-receipt.edit', $singleIncomeReceiptData->id)}}">
                                                <div class="col-12 bg-warning text-center py-2">
                                                    <i class="material-symbols-outlined custom-bill-pay-icon">edit_note</i> Modify Receipt
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="">
                            <div class="card card-stats">
                                <div class="card-header custom-payment-section-header">
                                    <div class="icon icon-warning d-flex">
                                        <span class="material-symbols-outlined">equalizer</span>
                                        <p
                                            class="category custom-card-header-title custom-payment-section-heading-title">
                                            <strong>Receipt Notes</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-content px-3 custom-payment-section-body">
                                    <span>
                                        <div class="form-group custom-select2-form">
                                            <label for="receipt_notes"> Notes <span
                                                    class=" text-danger">(required)</span>
                                            </label>

                                            <textarea placeholder="Enter Notes" class="form-control"
                                                name="receipt_notes" id="receipt_notes" cols="2" rows="2"
                                                required>{{$singleIncomeReceiptData->receipt_notes}}</textarea>
                                        </div>

                                        @error('receipt_notes')
                                        <span class=text-danger>{{$message}}</span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    <!-- all content will be here. end -->

</div>

@endsection

@section('scripts')
<script>
</script>
@endsection

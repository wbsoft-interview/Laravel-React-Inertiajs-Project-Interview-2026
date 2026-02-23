@extends('backend.master')
@section('title') Invoice | Beauty Parlour @endsection
@section('lead') active @endsection
@section('lead.index') active @endsection
@section('styles')
<style>
    .custom-billing-invoice-logo img {
        height: 40px;
        width: 200px;
        margin-top: 25px;
    }
</style>
@endsection


@section('main_content_section')

<div class="row min-vh-100">
    <!-- all content will be here. start -->

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
                            <img src="{{ asset('backend/uploads/invoiceLogoImg/'.$softLogo->logo_image) }}" alt="logo">
                            @else
                            <img src="{{asset('backend')}}/billing_invoice_logo.png" alt="logo">
                            @endif
                        </div>
                    </div>
                    <div class="custom-issue-due-date-section">

                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex flex-column justify-content-end custom-issue-due-date-text-area gap-2">
                                <p class="">Receipt Id: </p>
                                <p class="">Date: </p>
                                <p class="">Reciever: </p>
                            </div>
                            
                            <div class="d-flex flex-column gap-2 custom-billing-invoice-header-right">
                                <input type="text" class="form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                    value="#{{$singleBillingData->expense_receipt_id}}" readonly>
                                <input type="text" class=" form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                    name="date_issue" id="date_issue" placeholder="DD-MM-YYYY"
                                    value="{{Carbon\Carbon::parse($singleBillingData->created_at->toDateString())->format('d-m-Y')}}" readonly>
                                <input type="text" class="form-control custom-readonly-bg-color flatpickr-basic custom-date-picker"
                                    value="{{Str::title(Auth::user()->name)}}" readonly>
                            </div>
                        </div>

                    </div>
                </div>
                <hr>

                <hr>
                <div class="row px-4">
                    <div class="col-12">
                        <div class="table-container table-responsive">
                            <table class="table table-bordered py-3">
                                <!-- <hr class="mb-1"> -->
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
                                    @foreach($serviceBPP as $singleBPP)
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
                    </div>
                </div>

                <hr>

                <div class="custom-invoice-common-padding pb-5">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="custom-text-justify">
                                
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="w-100 d-flex gap-2">
                                <div class="text-end w-50 custom-footer-right-text-block">
                                    <p>Expense Service Qty:</p>
                                    <p>Total Amount:</p>
                                    <p>Total Paid:</p>
                                    <p>Total Due:</p>
                                </div>
                                <div class="d-flex flex-column gap-2 w-50">
                                    <input type="text" name="sub_total_quantity" id="sub_total_quantity"
                                        class="form-control custom-cursor-pointer custom-readonly-bg-color"
                                        value="{{ $subTotalProductQty > 0 ? $subTotalProductQty : 0}}" readonly>
                                   <input type="text" name="grand_total_price" id="grand_total_price"
                                        class="form-control custom-cursor-pointer custom-readonly-bg-color"
                                        value="{{ $grandTotalProductPrice > 0 ? $grandTotalProductPrice : 0}}" readonly>
                                    <input type="text" name="grand_total_paid" id="grand_total_paid"
                                        class="form-control custom-cursor-pointer custom-readonly-bg-color" value="{{ $grandTotalProductPaidPrice > 0 ? $grandTotalProductPaidPrice : 0}}" readonly>
                                    <input type="text" name="grand_total_due" id="grand_total_due"
                                        class="form-control custom-cursor-pointer custom-readonly-bg-color"
                                        value="{{ $grandTotalProductDuePrice > 0 ? $grandTotalProductDuePrice : 0}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    <p class="category custom-card-header-title custom-payment-section-heading-title">
                                        <strong>Invoice Status</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="card-footer p-0 dashboard-card-footer text-center custom-invoice-card-status">
                                <div class="stats">
                                    <div class="row">
                                        <div class="col-12">
                                            <i class="material-symbols-outlined">check_circle</i><br>
                                            <span>Completed</span>
                                        </div>
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
                                    <p class="category custom-card-header-title custom-payment-section-heading-title">
                                        <strong>Invoice Options</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="card-footer p-0 dashboard-card-footer">
                                
                                <div class="stats">
                                    <div class="row mt-2">

                                        <a href="{{route('print-expense-receipt-payment-invoice-page', $singleBillingData->id)}}" 
                                        {{-- <a href="#" --}}
                                            class="text-white btn" target="_blank">
                                            <div class="col-12 invoice-print-button text-center py-2">
                                                <i class="material-symbols-outlined custom-bill-pay-icon">print</i>Print
                                            </div>
                                        </a>
                                        
                                        <a href="{{route('download-expense-receipt-payment-invoice-page', $singleBillingData->id)}}" 
                                        {{-- <a href="#"  --}}
                                            class="text-white btn" target="_download">
                                            <div class="col-12 invoice-download-button text-center py-2">
                                                <i class="material-symbols-outlined custom-bill-pay-icon">visibility</i>View
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
                                    <p class="category custom-card-header-title custom-payment-section-heading-title">
                                        <strong>Notes</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="card-footer p-0 dashboard-card-footer text-center custom-invoice-card-status">
                                <div class="stats">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-0">{{$singleBillingData->receipt_notes}}</p>
                                        </div>
                                    </div>
                                </div>
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
@endsection
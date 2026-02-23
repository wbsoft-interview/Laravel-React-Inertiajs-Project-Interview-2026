@extends('backend.master')
@section('title') Income Receipt | Amali @endsection
@section('get-client-due-bill-payment-invoice') active @endsection
@section('get-client-due-bill-payment-invoice') active @endsection
@section('styles')
<style>
    .custom-billing-invoice-logo img{
        height: auto !important;
        width: auto !important;
    }
</style>
@endsection


@section('main_content_section')

<div class="row min-vh-100">
    <!-- all content will be here. start -->
    <form action="{{route('save-all-income-receipt')}}" method="POST" enctype="multipart/form-data" id="billingRootForm"
        class="p-0">
        @csrf
        <input type="hidden" name="income_receipt_id" id="income_receipt_id" value="{{$singleIncomeReceiptData->id}}">

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
                                <img src="{{ asset('backend/uploads/invoiceLogoImg/'.$softLogo->logo_image) }}" alt="logo" class="mt-4">
                                @else
                                <img src="{{asset('backend')}}/billing_invoice_logo.png" alt="logo" class="mt-4">
                                @endif
                            </div>
                        </div>
                        <div class="custom-issue-due-date-section">

                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex flex-column justify-content-end custom-issue-due-date-text-area gap-2">
                                    <p class="">Date: </p>
                                    <p class="">Reciever: </p>
                                </div>

                                <div class="d-flex flex-column gap-2 custom-billing-invoice-header-right">
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
                                                        <b>Category: </b>{{$item->incomeCategoryData->category_name}} <br>
                                                        <b>Income: </b>{{$item->incomeData->income_name}}
                                                    </div>
                                                    <div class="row-actions w-50">
                                                        @if (Auth::user()->can('billing-edit'))
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="text-start w-50">
                                                    <span class="text-secondary">{{$item->income_details}}</span>
                                                </td>

                                                <td class="text-center">
                                                    <span class="fw-bolder text-success">{{$item->income_amount}}</span>
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
                                            <input type="text" name="sub_total_income_quantity" id="sub_total_income_quantity"
                                                class="form-control custom-cursor-pointer custom-readonly-bg-color"
                                                value="{{ $totalIncomeServiceQty > 0 ? $totalIncomeServiceQty : 0}}" readonly>
                                            <input type="text" name="sub_total_income_amount" id="sub_total_income_amount"
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
                                        <p class="category custom-card-header-title custom-payment-section-heading-title">
                                            <strong>Select Income</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-footer p-0 dashboard-card-footer">
                                    <div class="stats">
                                        <div class="row">
                                            <a href="javascript(void.0)"
                                                class="text-white btn" data-bs-toggle="modal"
                                                data-bs-target="#addIncomeReceipt">
                                                <div class="col-12 invoice-print-button text-center py-2">
                                                    <i class="fa fa-plus"></i>
                                                    <span class="pl-4">Add</span>
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
                                            <strong>Receipt Notes</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-content px-3 custom-payment-section-body">
                                    <span>
                                        <div class="form-group custom-select2-form">
                                            <label for="receipt_notes"> Notes <span class=" text-danger">*</span>
                                            </label>

                                            <textarea placeholder="Enter Notes" class="form-control" name="receipt_notes" id="receipt_notes"
                                                cols="2" rows="2" required></textarea>
                                        </div>

                                        @error('receipt_notes')
                                        <span class=text-danger>{{$message}}</span>
                                        @enderror
                                    </span>
                                </div>
                                <div class="card-footer p-0 dashboard-card-footer">
                                    <div class="stats">
                                        <div class="row">
                                            <button type="submit" class="text-white btn">
                                                <div class="col-12 bg-success text-center py-2">Save Receipt</div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </form>
    <!-- all content will be here. end -->

    {{-- //Add new income.. --}}
    <div class="modal fade" id="addIncomeReceipt" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
        data-bs-backdrop='static'>
        <div class="modal-dialog modal-dialog-centered max-width-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="oneInputModalLabel">Add Income To Receipt</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post" enctype="multipart/form-data" onsubmit="return addNewIncomeReceipt()">
                    @csrf

                    <div class="modal-body p-0">
                        <div class="row px-4 my-4">

                            <div class="col-md-6 mb-3">
                                <div class="form-group custom-select2-form">
                                    <label for="income_category_id">Income Category <span class=" text-danger">*</span>
                                    </label>
                                    <select name="income_category_id" id="income_category_id" class="form-select select2"
                                        onchange="getIncomeDataWithCate()" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach($incomeCategoryData as $singleECD)
                                        <option value="{{$singleECD->id}}">{{$singleECD->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('income_category_id')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group custom-select2-form">
                                    <label for="income_id">Income <span class=" text-danger">*</span>
                                    </label>
                                    <select name="income_id" id="income_id" class="form-select select2" required>
                                        <option value="" selected disabled>Select Income</option>

                                    </select>
                                </div>

                                @error('income_id')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group custom-select2-form">
                                <label for="receiver_id">Receiver <span class=" text-danger">*</span>
                                </label>
                                <select name="receiver_id" id="receiver_id" class="form-select select2" required>
                                    <option value="" selected disabled>Select Receiver</option>
                                    @foreach($receiverData as $singlePD)
                                    <option value="{{$singlePD->id}}">{{$singlePD->receiver_name}} / {{$singlePD->receiver_phone}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @error('receiver_id')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="income_amount">Income Amount<span class="text-danger">*</span>
                                </label>
                                <input type="number" name="income_amount" id="income_amount" required class="form-control form-control-solid"
                                    value="" step="0.01" placeholder="Income Amount">
                            </div>

                            @error('income_amount')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="income_details">Income Details<span class="text-danger">*</span>
                                </label>
                                <textarea rows="3" cols="3" name="income_details" id="income_details" required class="form-control form-control-solid"
                                    value="" placeholder="Income Details"></textarea>
                            </div>

                            @error('income_details')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>

@endsection

@section('scripts')
<script>
    $("#income_category_id").select2({
        dropdownParent: $('#addIncomeReceipt')
    });
    $("#income_id").select2({
        dropdownParent: $('#addIncomeReceipt')
    });
    $("#receiver_id").select2({
        dropdownParent: $('#addIncomeReceipt')
    });

    //To add new income receipt...
    function addNewIncomeReceipt() {
        event.preventDefault();
        var income_receipt_id = $("#income_receipt_id").val();
        var income_category_id = $("#income_category_id").val();
        var income_id = $("#income_id").val();
        var receiver_id = $("#receiver_id").val();
        var income_amount = $("#income_amount").val();
        var income_details = $("#income_details").val();
        var url = "{{ route('income-receipt.store') }}";
        if(income_receipt_id != ''){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    income_receipt_id: income_receipt_id,
                    income_category_id: income_category_id,
                    income_id: income_id,
                    receiver_id: receiver_id,
                    income_amount: income_amount,
                    income_details: income_details
                },
                success: function (data) {
                    if(data.error){
                        toastr.error(data.error);
                    }else{
                        $('#addIncomeReceipt').modal('hide');
                        toastr.success('IncomeReceipt created successfully.');
                        $('#income_category_id').val(null).trigger("change");
                        $('#income_id').val(null).trigger("change");
                        $('#receiver_id').val(null).trigger("change");
                        $('#income_amount').val('');
                        $('#income_details').val('');
                        $('#updateIncomeDetails').html(data);

                        let updateServiceQty = $('#update_income_service_qty').val();
                        let updateServiceAmount = $('#update_income_service_amount').val();
                        $('#sub_total_income_quantity').val(updateServiceQty);
                        $('#sub_total_income_amount').val(updateServiceAmount);
                    }
                }
            });
        }
    }

    //To edit income service data...
    function editIncomeReceipt(id) {
        event.preventDefault();
        $('#editIncomeReceipt'+id).modal('show');
        $("#income_category_id"+id).select2({
            dropdownParent: $('#editIncomeReceipt'+id)
        });
        $("#income_id"+id).select2({
            dropdownParent: $('#editIncomeReceipt'+id)
        });

        $("#receiver_id"+id).select2({
            dropdownParent: $('#editIncomeReceipt'+id)
        });

    }

    //To update income receipt...
    function updateIncomeReceipt(id) {
        event.preventDefault();
        var income_service_id = id;
        var income_receipt_id = $("#income_receipt_id"+id).val();
        var income_category_id = $("#income_category_id"+id).val();
        var income_id = $("#income_id"+id).val();
        var receiver_id = $("#receiver_id"+id).val();
        var income_amount = $("#income_amount"+id).val();
        var income_details = $("#income_details"+id).val();
        var url = "{{ route('income-receipt.update') }}";
        if(income_receipt_id != ''){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    income_service_id: income_service_id,
                    income_receipt_id: income_receipt_id,
                    income_category_id: income_category_id,
                    income_id: income_id,
                    receiver_id: receiver_id,
                    income_amount: income_amount,
                    income_details: income_details
                },
                success: function (data) {
                    if(data.error){
                        toastr.error(data.error);
                    }else{
                        $('#editIncomeReceipt'+id).modal('hide');
                        toastr.success('IncomeReceipt updated successfully.');
                        $('#income_category_id'+id).val(null).trigger("change");
                        $('#income_id'+id).val(null).trigger("change");
                        $('#receiver_id'+id).val(null).trigger("change");
                        $('#income_amount'+id).val('');
                        $('#income_details'+id).val('');
                        $('#updateIncomeDetails').html(data);

                        let updateServiceQty = $('#update_income_service_qty').val();
                        let updateServiceAmount = $('#update_income_service_amount').val();
                        $('#sub_total_income_quantity').val(updateServiceQty);
                        $('#sub_total_income_amount').val(updateServiceAmount);
                    }
                }
            });
        }
    }

    //To remove income service data...
    function deleteIncomeServiceData(id) {
        event.preventDefault();

        var url = "{{ route('income-receipt-delete') }}";
        if(id != ''){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    income_service_id: id,
                },
                success: function (data) {
                    if(data.error){
                        toastr.error(data.error);
                    }else{
                        toastr.success('Income service removed successfully.');
                        $('#updateIncomeDetails').html(data);

                        let updateServiceQty = $('#update_income_service_qty').val();
                        let updateServiceAmount = $('#update_income_service_amount').val();
                        $('#sub_total_income_quantity').val(updateServiceQty);
                        $('#sub_total_income_amount').val(updateServiceAmount);
                    }
                }
            });
        }
    }

    //To get Income with .
    function getIncomeDataWithCate(){
      var income_category_id = $("#income_category_id").val();
      var url = "{{ route('get-all-income-with-category-id') }}";
        if (income_category_id != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    income_category_id: income_category_id
                },
                success: function (data) {

                    //For Section...
                    $("#income_id").empty();
                    $("#income_id").append('<option value="" selected disabled>Select Income</option>');

                    $.each(data, function(key,value){
                    $("#income_id").append('<option value="'+value.id+'">'+value.income_name+'</option>');
                    });

                }

            });
        }
    };

    //To get Income with .
    function getIncomeDataWithCateFU(id){
      var income_category_id = $("#income_category_id"+id).val();
      var url = "{{ route('get-all-income-with-category-id') }}";
        if (income_category_id != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    income_category_id: income_category_id
                },
                success: function (data) {

                    //For Section...
                    $("#income_id"+id).empty();
                    $("#income_id"+id).append('<option value="" selected disabled>Select Income</option>');

                    $.each(data, function(key,value){
                    $("#income_id"+id).append('<option value="'+value.id+'">'+value.income_name+'</option>');
                    });

                }

            });
        }
    };
</script>
@endsection
